<?php

namespace App\Application\UseCase\ListUser;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Domain\RepositoryContract\UserRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListUser
{
    private DriverRepositoryContract $driverRepository;
    private HostRepositoryContract $hostRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->driverRepository = $repositoryFactory->getDriverRepository();
        $this->hostRepository = $repositoryFactory->getHostRepository();
    }

    public function execute(ListUserInput $input)
    {
       // $employeeType = Auth::getLogged()->getEmployeeType();

        $page = null;

        if($input->type == 'driver'){
        $page = $this->driverRepository->searchDrivers(
            limit: $input->limit,
            offset: $input->offset,
            filters: $input->filters,
        );
        } else {
        $page = $this->hostRepository->searchHost(
            limit: $input->limit,
            offset: $input->offset,
            filters: $input->filters,
        );
        }

        return $page->toJSON();
    }
}
