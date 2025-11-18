<?php

namespace App\Application\UseCase\CreateDriver;

use App\Domain\Entity\Auth;
use App\Domain\Entity\Driver;
use App\Domain\Entity\Employee;
use App\Domain\Entity\Service\EntityRelations\EntityRelationService;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Entity\ValueObject\GenerateRandomPassword;
use App\Domain\Entity\ValueObject\Password;
use App\Domain\Exception\{NotAcceptableException, InternalException};
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
};

class CreateDriver
{
    private DriverRepositoryContract         $driverRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->driverRepository    = $repositoryFactory->getDriverRepository();
    }


    public function execute(CreateDriverInput $input): void
    {

        // valida email
        if ($this->driverRepository->findOneBy(["email" => $input->email])) {
            throw new NotAcceptableException('Email already used.');
        }

        // valida telefone
        if ($this->driverRepository->findOneBy(["phone" => $input->phone])) {
            throw new NotAcceptableException('Phone already used.');
        }

        // senha
         $generateRandomPassword = new GenerateRandomPassword();
        $password = $generateRandomPassword->getValue();
        $passwordValidated = new Password($password);
        $passwordHash = $passwordValidated->getPasswordHash();

        try {
            $createdDriver = $this->driverRepository->create(
                Driver::create(
                    name: $input->name,
                    email: $input->email,
                    phone: $input->phone,
                )
            );

            $this->whatsappService->sendPassword(
                phone: $createdDriver->getPhone(),
                password: $password, // senha em texto puro pro usuário
                entityId: $createdDriver->getId()
            );
        } catch (InternalException) {
            throw new InternalException("Error creating Employee.");
        }
    }
}
