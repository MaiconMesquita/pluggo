<?php

namespace App\Application\UseCase\CreateAnEmployee;

use App\Domain\Entity\Auth;
use App\Domain\Entity\Employee;
use App\Domain\Entity\Service\EntityRelations\EntityRelationService;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Entity\ValueObject\LowEntity;
use App\Domain\Entity\ValueObject\Password;
use App\Domain\Exception\{NotAcceptableException, InternalException};
use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Domain\RepositoryContract\EntityRelationRepositoryContract;
use App\Domain\RepositoryContract\EstablishmentRepositoryContract;
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
    ServiceFactoryContract,
};

class CreateAnEmployee
{
    private EmployeeRepositoryContract         $employeeRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->employeeRepository    = $repositoryFactory->getEmployeeRepository();
    }


    public function execute(CreateAnEmployeeInput $input): void
    {

        // valida email
        if ($this->employeeRepository->findOneBy(["email" => $input->email])) {
            throw new NotAcceptableException('Email already used.');
        }

        // valida telefone
        if ($this->employeeRepository->findOneBy(["phone" => $input->phone])) {
            throw new NotAcceptableException('Phone already used.');
        }
        // senha
        $password = $input->password;
        $passwordValidated = !empty($password)
            ? new Password($password, verifyIfIStrong: false)
            : new Password("Brands123@");

        $hash = $passwordValidated->getPasswordHash();

        try {
            $createdEmployee = $this->employeeRepository->create(
                Employee::create(
                    name: $input->name,
                    email: $input->email,
                    phone: $input->phone,
                    password: $hash,
                    cpf: $input->cpf,
                )
            );
           
        } catch (InternalException) {
            throw new InternalException("Error creating Employee.");
        }
    }
}
