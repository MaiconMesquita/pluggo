<?php

namespace App\Domain\Entity\Service\SessionTokenService;

use App\Domain\Entity\{LoginHistory, Token};
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\ValueObject\{EmployeeType, EntityType, TokenType, UserType};
use App\Domain\Exception\{AccountLockedByTooManyAttemptsException, BlockingByScoreException, InternalException, InvalidAuthException, InvalidDataException, NotAcceptableException};
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\{RepositoryFactoryContract, ThirdPartyFactoryContract};
use App\Infra\Repository\{LoginHistoryRepository, TokenRepository};
use App\Infra\ThirdParty\JWT\JWT;
use App\Infra\ThirdParty\Logging\Logging;

class SessionTokenService
{
    private LoginHistoryRepository $loginHistoryRepository;
    private TokenRepository $tokenRepository;
    private DriverRepositoryContract         $driverRepository;
    private HostRepositoryContract         $hostRepository;
    private JWT $jwt;
    private Logging $logging;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ThirdPartyFactoryContract $thirdPartyFactory,
    ) {
        $this->tokenRepository = $repositoryFactory->getTokenRepository();
        $this->logging = $thirdPartyFactory->getLogging();
        $this->driverRepository         = $repositoryFactory->getDriverRepository();
        $this->hostRepository         = $repositoryFactory->getHostRepository();   
        $this->jwt = $thirdPartyFactory->getJWT();
    }

    public function generateToken(SessionTokenServiceInput $input): SessionTokenServiceOutput
    {
        // Step 1: Fetch the user/employee based on input criteria
        $driver = $input->driver;
        $host = $input->host;
        $password = $input->password;
        $name = null;
        $typeEntity = null;
        $driverId = null;
        $hostId = null;
        if (($driver && $host) || (!$driver && !$host)) {
            throw new NotAcceptableException("Only user or employee must be provided, not both.");
        }  

        $now = DateTimeOffset::getAdjustedDateTime();

        if($driver){        

            $this->validateBlockedEntity($driver, $password, true);
    
            $driverId = $driver->getId();
            $name = $driver->getName();
        }else{
            $this->validateBlockedEntity($host, $password, false);

            $hostId = $host->getId();
            $name = $host->getName();
        }

        // Step 3: Revoke previous tokens
        $id = $driverId ? $driverId : $hostId;
        try {
            $this->tokenRepository->revokeTokenByUserId(id: $id, realUser: $driverId ? true : false);
        } catch (InternalException) {
            throw new InternalException("Error deleting user session.");
        }

        // Step 4: Generate the new access token
        $authType = $driverId ? "driver" : "host";

        $tokenExpiration = (int) $_ENV['TOKEN_EXPIRATION_TIME'];
        $payloadToken = ["uid" => $id, "authType" => $authType];

        $token = $this->jwt->encode($payloadToken, $tokenExpiration);

        // Step 5: Generate the refresh token
        $refreshTokenExpiration = (int) $_ENV['REFRESH_TOKEN_EXPIRATION_TIME'];
        $payloadRefreshToken = [
            'tid' => $token->id,
            'uid' => $id,
            "authType" => $authType
        ];
        $refreshToken = $this->jwt->encode($payloadRefreshToken, $refreshTokenExpiration);
        
        $type = new TokenType(TokenType::REFRESH_TOKEN);

        try {
            $this->tokenRepository->create(
                Token::create(
                    token: $refreshToken,
                    driverId: $driverId,
                    hostId: $hostId,
                    type: $type
                )
            );
        } catch (InternalException) {
            throw new InternalException("Error creating user session.");
        }

        // Step 6: Return the tokens in the output class
        $output = new SessionTokenServiceOutput();
        $output->name = $name;
        $output->accessToken = $token->token;
        $output->refreshToken = $refreshToken->token;
        $output->expiresIn = $tokenExpiration;

        return $output;
    }

    private function validateBlockedEntity(object $entity, string $password, string $entityType): void
    {
        $now = DateTimeOffset::getAdjustedDateTime();
        $repository = $entityType == EntityType::DRIVER ? $this->driverRepository : $this->hostRepository;
        $label = $entityType;

        if (!$entity->passwordVerify($password)) {
            $lastUpdate = $entity->getUpdatedAt();
            $passwordAttempt = $entity->getPasswordAttempt();

            $interval = $now->getTimestamp() - $lastUpdate->getTimestamp();
            if ($interval >= 60 && $passwordAttempt > 0) {
                $passwordAttempt = 0;
            }

            $passwordAttempt += 1;

            if ($passwordAttempt >= 4) {
                $entity->setStatus(false);
                $entity->setPasswordAttempt($passwordAttempt);
                $repository->update($entity);
                $this->logging->info("$label inactive");
                throw new AccountLockedByTooManyAttemptsException();
            }

            $entity->setPasswordAttempt($passwordAttempt);
            $repository->update($entity);
            $this->logging->info("Passwords don't match");
            throw new InvalidAuthException();
        }

        if (!$entity->getStatus()) {
            $this->logging->info("$label inactive");
            throw new InvalidAuthException();
        }

        if ($entity->getPasswordAttempt() > 0) {
            $entity->setPasswordAttempt(0);
            $repository->update($entity);
        }
    }

}




