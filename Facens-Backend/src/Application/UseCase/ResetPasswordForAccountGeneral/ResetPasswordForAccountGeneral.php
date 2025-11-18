<?php

namespace App\Application\UseCase\ResetPasswordForAccountGeneral;

use App\Domain\Entity\Service\BrevoService\BrevoEmailInput;
use App\Domain\Entity\Service\BrevoService\BrevoRequests;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\Service\RegistrationValidation\RegistrationValidation;
use App\Domain\Entity\Service\SmsService\{SmsService, SmsServiceConnectify, SmsServiceConnectifyInput, SmsServiceInput};
use App\Domain\Entity\Service\ValidateDocument\ValidateDocument;
use App\Domain\Entity\ValueObject\{Document, Email, EmployeeType, GenerateRandomPassword, Password, PhoneNumber, SmsType, UserType};
use App\Domain\Exception\{AccessDeniedByHierarchyException, DeviceNotFoundException, InternalException, InvalidDataException, NotAcceptableException};
use App\Domain\RepositoryContract\{EmployeeRepositoryContract, EstablishmentRepositoryContract, SmsHistoryRepositoryContract, UserRepositoryContract};
use App\Infra\Factory\Contract\{RepositoryFactoryContract, ServiceFactoryContract, ThirdPartyFactoryContract};
use DateTime;

class ResetPasswordForAccountGeneral
{
    private ValidateDocument $validateDocument;
    private array $repositories;
    private BrevoRequests $brevoRequests;


    public function __construct(
        private ThirdPartyFactoryContract $thirdPartyFactory,
        private RepositoryFactoryContract $repositoryFactory,
        private ServiceFactoryContract $serviceFactory
    ) {
        $this->validateDocument = $serviceFactory->getValidateDocument();
        $this->brevoRequests = new BrevoRequests($thirdPartyFactory, $repositoryFactory, $serviceFactory);
        $this->repositories = [
            'driver'          => $repositoryFactory->getDriverRepository(),
            'host'      => $repositoryFactory->getHostRepository(),
        ];
    }

    public function execute(ResetPasswordForAccountGeneralInput $input): void
    {
        $emailValidation = new Email($input->email);
        $email = $emailValidation->getValue();
        $entityType = $input->entity;
        $entity = null;

        // Buscar entidade pelo tipo
        if ($entityType && isset($this->repositories[$entityType])) {
            $repo = $this->repositories[$entityType];
            $entity = $repo->findOneBy(["email" => $email]);
            if (!$entity) {
                throw new InvalidDataException(ucfirst($entityType) . " not found.");
            }
        } else {
            throw new InvalidDataException("Unsupported entity type: {$entityType}");
        }

        $generateRandomPassword = new GenerateRandomPassword();
        $secret = $generateRandomPassword->getValue();
        $passwordValidated = new Password($secret);
        $passwordHash = $passwordValidated->getPasswordHash();

        $brevoInput = new BrevoEmailInput(
            toName: $entity->getName(),
            toEmail: $entity->getEmai(),
            subject: 'Reset de senha',
            htmlContent: "<p>Olá <b>{$entity->getName()}</b>!<br>Sua nova senha é <b>{$secret}</b></p>",
        );

        $this->brevoRequests->sendEmail($brevoInput);
        $entity->setPassword($passwordHash);
        try {
            $this->repositories[$entityType]->update($entity);
        } catch (InternalException) {
            throw new InternalException("Error updating {$entityType}.");
        }
    }
}
