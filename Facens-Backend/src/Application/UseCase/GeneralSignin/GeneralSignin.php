<?php

namespace App\Application\UseCase\GeneralSignin;

use App\Domain\Entity\Service\ConnectifyService\ConnectifyServiceRequests;
use App\Domain\Entity\Service\RegistrationValidation\RegistrationValidation;
use App\Domain\Entity\Service\SessionTokenService\{
    GeneralSessionTokenServiceInput,
    GeneralSessionTokenService,
    SessionTokenServiceOutput
};
use App\Domain\Entity\Service\ValidateDocument\ResolveDocumentField;
use App\Domain\Exception\DeviceNotFoundException;
use App\Domain\Exception\IncompleteRegistrationException;
use App\Domain\Exception\InvalidAuthException;
use App\Domain\Exception\InvalidDataException;
use App\Domain\Exception\NotAcceptableException;
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
    ThirdPartyFactoryContract
};

class GeneralSignin
{
    private array $repositories; // lista de repositórios genéricos
    private GeneralSessionTokenService $sessionTokenService;
    private RegistrationValidation $validationService;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ThirdPartyFactoryContract $thirdPartyFactory
    ) {
        $this->repositories = [
            'driver'          => $repositoryFactory->getDriverRepository(),
            'host'      => $repositoryFactory->getHostRepository(),
        ];

        $this->sessionTokenService = new GeneralSessionTokenService(
            thirdPartyFactory: $thirdPartyFactory,
            repositoryFactory: $repositoryFactory
        );

        $this->validationService = new RegistrationValidation();
    }

    public function execute(GeneralSigninInput $input): SessionTokenServiceOutput
    {
        $email      = $input->email;
        $password   = $input->password;
        $entityType = $input->entityType;
        $entity     = null;

        // Validação obrigatória mínima
        if (!$password) {
            throw new InvalidDataException("Password is required.");
        }

        // 1. Buscar a entidade
        if ($entityType && isset($this->repositories[$entityType])) {
            $repo = $this->repositories[$entityType];

            
                if (!$email) {
                    throw new InvalidDataException("Email is required for {$entityType}.");
                }
                $entity = $repo->findOneBy(['email' => $email]);
            

            if (!$entity) {
                throw new NotAcceptableException("No {$entityType} found with given credentials.");
            }
        } else {
            // fallback: tenta em todos os repositórios
            foreach ($this->repositories as $type => $repo) {
                if ($email) {
                    $entity = $repo->findOneBy(['email' => $email]);
                }

                if ($entity) {
                    $entityType = $type;
                    break;
                }
            }

            if (!$entity) {
                throw new NotAcceptableException("No entity found with given credentials.");
            }
        }

        // 5. Gerar token
        $sessionToken = new GeneralSessionTokenServiceInput(
            entity: $entity,
            entityType: $entityType,
            password: $password
        );

        return $this->sessionTokenService->generateToken($sessionToken);
    }
}
