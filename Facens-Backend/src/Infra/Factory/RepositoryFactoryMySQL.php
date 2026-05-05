<?php

namespace App\Infra\Factory;

use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Infra\Database\Doctrine;

use App\Domain\RepositoryContract\{
    ApiKeyRepositoryContract,
    DriverRepositoryContract,
    HostRepositoryContract,
    TokenRepositoryContract,
    ChargeSpotsRepositoryContract,
    EmployeeRepositoryContract,
    SpotReviewRepositoryContract
};
use App\Infra\Repository\{
    ApiKeyRepository,
    DriverRepository,
    HostRepository,
    TokenRepository,
    ChargeSpotsRepository,
    EmployeeRepository,
    SpotReviewRepository
};

class RepositoryFactoryMySQL implements RepositoryFactoryContract
{
    public function __construct(
        private Doctrine $dbInstace,
    ) {
    }
    public function getDriverRepository(): DriverRepositoryContract
    {
        return new DriverRepository($this->dbInstace->getEntityManager());
    }

    public function getChargeSpotsRepository(): ChargeSpotsRepositoryContract
    {
        return new ChargeSpotsRepository($this->dbInstace->getEntityManager());
    }

    public function getEmployeeRepository(): EmployeeRepositoryContract
    {
        return new EmployeeRepository($this->dbInstace->getEntityManager());
    }

    public function getReviewSpotRepository(): SpotReviewRepositoryContract
    {
        return new SpotReviewRepository($this->dbInstace->getEntityManager());
    }


    public function getHostRepository(): HostRepositoryContract
    {
        return new HostRepository($this->dbInstace->getEntityManager());
    }

    public function getApiKeyRepository(): ApiKeyRepositoryContract
    {
        return new ApiKeyRepository($this->dbInstace->getEntityManager());
    }

    public function getTokenRepository(): TokenRepositoryContract
    {
        return new TokenRepository($this->dbInstace->getEntityManager());
    }

}
