<?php

namespace App\Application\UseCase\ListUser;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\RepositoryContract\UserRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListUser
{
    private UserRepositoryContract $userRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->userRepository = $repositoryFactory->getUserRepository();        
    }

    public function execute(ListUserInput $input)
    {
        $employeeType = Auth::getLogged()->getEmployeeType();   

        $page = $this->userRepository->searchUsers(
            limit: $input->limit,
            offset: $input->offset,
            filter: $input->filter,
            field: $input->field,            
            support: $employeeType == EmployeeType::SUPPORT ? true : false
        );

        return $page->toJSON();
    }
}
