<?php

namespace App\Infra\Factory;

use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Infra\Database\Doctrine;

use App\Domain\RepositoryContract\{
    ApiKeyRepositoryContract,
    DriverRepositoryContract,
    HostRepositoryContract,
    TokenRepositoryContract,
    ChargeSpotsRepositoryContract
};
use App\Infra\Repository\{
    ApiKeyRepository,
    DriverRepository,
    HostRepository,
    TokenRepository,
    ChargeSpotsRepository
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
