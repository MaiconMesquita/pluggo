<?php

namespace App\Application\UseCase\SigninEmployee;

use App\Domain\Entity\Service\RegistrationValidation\RegistrationValidation;
use App\Domain\Entity\Service\SessionTokenService\{
    SessionTokenService, 
    SessionTokenServiceInput, 
    SessionTokenServiceOutput};
use App\Domain\Exception\NotAcceptableException;
use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
    ThirdPartyFactoryContract
};

class SigninEmployee
{
    private EmployeeRepositoryContract $employeeRepository;
    private SessionTokenService $sessionTokenService;
    private RegistrationValidation $validationService;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ThirdPartyFactoryContract $thirdPartyFactory
    ) {
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository();
        $this->sessionTokenService = new SessionTokenService(thirdPartyFactory: $thirdPartyFactory, repositoryFactory: $repositoryFactory);
        $this->validationService = new RegistrationValidation();
    }

    public function execute(SigninEmployeeInput $input): SessionTokenServiceOutput
    {
        $employee = $this->employeeRepository->findOneBy(["email" => $input->email]);

        if (!$employee)throw new NotAcceptableException('The email provided is not registered.');
        
        $customFields = [
            'name',
            'phone',
            'email'
        ];

        $this->validationService->validate(entityType: 'employee', entity: $employee, customFields: $customFields);

        $sessionToken = new SessionTokenServiceInput(
            employee: $employee,
            password: $input->password,
        );

        $output = $this->sessionTokenService->generateToken($sessionToken);

        return $output;
    }
}

