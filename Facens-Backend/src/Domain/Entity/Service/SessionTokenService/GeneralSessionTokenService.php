<?php

namespace App\Domain\Entity\Service\SessionTokenService;

use App\Domain\Entity\{LoginHistory, Token};
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\ValueObject\{TokenType, UserType};
use App\Domain\Exception\{AccountLockedByTooManyAttemptsException, BlockingByScoreException, InternalException, InvalidAuthException, InvalidDataException, NotAcceptableException};
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Infra\Factory\Contract\{RepositoryFactoryContract, ThirdPartyFactoryContract};
use App\Infra\Repository\{LoginHistoryRepository, TokenRepository};
use App\Infra\ThirdParty\JWT\JWT;
use App\Infra\ThirdParty\Logging\Logging;

class GeneralSessionTokenService
{
    private TokenRepository $tokenRepository;
    private HostRepositoryContract         $hostRepository;
    private DriverRepositoryContract         $driverRepository;
    private JWT $jwt;
    private Logging $logging;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ThirdPartyFactoryContract $thirdPartyFactory,
    ) {
        $this->tokenRepository = $repositoryFactory->getTokenRepository();
        $this->logging = $thirdPartyFactory->getLogging();
        $this->hostRepository         = $repositoryFactory->getHostRepository();
        $this->driverRepository         = $repositoryFactory->getDriverRepository();
        $this->jwt = $thirdPartyFactory->getJWT();
    }

    public function generateToken(GeneralSessionTokenServiceInput $input): SessionTokenServiceOutput
    {
        $entity = $input->entity;
        $entityType = $input->entityType;
        $password = $input->password;
        $name = null;
        $typeEntity = null;
        $entityId = null;

        $now = DateTimeOffset::getAdjustedDateTime();

        // 1. Validar senha e status do entity (genérico)
        $this->validateBlockedEntity($entity, $password, $entityType);

        // 2. Preparar dados para login history e token
        
                $entityId = $entity->getId();
                $name = $entity->getName();

        

        // 4. Revogar tokens antigos
        try {
            $isUser = $entityType === 'user';
            $this->tokenRepository->revokeTokenByUserId(id: $entityId, realUser: $isUser);
        } catch (InternalException) {
            throw new InternalException("Error revoking previous tokens.");
        }

        // 5. Gerar token JWT
        $tokenExpiration = (int) $_ENV['TOKEN_EXPIRATION_TIME'];
        $payloadToken = ['uid' => $entityId, 'authType' => $entityType];
        $token = $this->jwt->encode($payloadToken, $tokenExpiration);

        // 6. Gerar refresh token
        $refreshTokenExpiration = (int) $_ENV['REFRESH_TOKEN_EXPIRATION_TIME'];
        $payloadRefreshToken = ['tid' => $token->id, 'uid' => $entityId, 'authType' => $entityType];
        $refreshToken = $this->jwt->encode($payloadRefreshToken, $refreshTokenExpiration);

        $type = new TokenType(TokenType::REFRESH_TOKEN);
        try {
            $this->tokenRepository->create(Token::create(
                token: $refreshToken,
                driverId: $entityType === 'driver' ? $entityId : null,
                hostId: $entityType === 'host' ? $entityId : null,
                type: $type
            ));
        } catch (InternalException) {
            throw new InternalException("Error creating token.");
        }

        // 7. Retornar output
        $output = new SessionTokenServiceOutput();
        $output->name = $name;
        $output->accessToken = $token->token;
        $output->refreshToken = $refreshToken->token;
        $output->expiresIn = $tokenExpiration;

        return $output;
    }

    /**
     * Validação genérica de senha e status
     */
    private function validateBlockedEntity(object $entity, string $password, string $entityType): void
    {
        $now = DateTimeOffset::getAdjustedDateTime();

        $repositoryMap = [
            'driver' => $this->driverRepository,
            'host' => $this->hostRepository,
        ];

        if (!isset($repositoryMap[$entityType])) {
            throw new InvalidDataException("No repository for entity type: $entityType");
        }

        $repository = $repositoryMap[$entityType];
        $label = ucfirst($entityType);

        // Só aplica lógica de senha se a entidade tiver passwordVerify
        if (method_exists($entity, 'passwordVerify') && !$entity->passwordVerify($password)) {

            $passwordAttempt = method_exists($entity, 'getPasswordAttempt') ? $entity->getPasswordAttempt() : 0;
            $lastUpdate = method_exists($entity, 'getUpdatedAt') ? $entity->getUpdatedAt() : $now;

            $interval = $now->getTimestamp() - $lastUpdate->getTimestamp();
            if ($interval >= 60 && $passwordAttempt > 0) {
                $passwordAttempt = 0;
                // Se estava bloqueado, libera de novo
                if (method_exists($entity, 'getStatus') && !$entity->getStatus()) {
                    if (method_exists($entity, 'setStatus')) {
                        $entity->setStatus(true);
                    }
                }

                if (method_exists($entity, 'setPasswordAttempt')) {
                    $entity->setPasswordAttempt($passwordAttempt);
                }
            }

            $passwordAttempt += 1;

            if ($passwordAttempt >= 4) {
                if (method_exists($entity, 'setStatus')) {
                    $entity->setStatus(false);
                }
                if (method_exists($entity, 'setPasswordAttempt')) {
                    $entity->setPasswordAttempt($passwordAttempt);
                }
                $repository->update($entity);
                $this->logging->info("$label inactive");
                throw new AccountLockedByTooManyAttemptsException();
            }

            if (method_exists($entity, 'setPasswordAttempt')) {
                $entity->setPasswordAttempt($passwordAttempt);
            }
            $repository->update($entity);
            $this->logging->info("Passwords don't match");
            throw new InvalidAuthException();
        }

        // Checa status
        if (method_exists($entity, 'getStatus') && !$entity->getStatus()) {
            $this->logging->info("$label inactive");
            throw new InvalidAuthException();
        }

        // Resetar tentativas se senha correta
        if (method_exists($entity, 'getPasswordAttempt') && $entity->getPasswordAttempt() > 0) {
            $entity->setPasswordAttempt(0);
            $repository->update($entity);
        }
    }
}
