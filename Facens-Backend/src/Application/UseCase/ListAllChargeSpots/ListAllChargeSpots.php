<?php

namespace App\Application\UseCase\ListAllChargeSpots;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListAllChargeSpots
{
    private HostRepositoryContract $hostRepositoryContract;
    private ChargeSpotsRepositoryContract $chargeSpotsRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->hostRepositoryContract = $repositoryFactory->getHostRepository();
        $this->chargeSpotsRepository = $repositoryFactory->getChargeSpotsRepository();
    }

    public function execute(ListAllChargeSpotsInput $input)
    {

        $spots = $this->chargeSpotsRepository->testFetchAll();

        return $spots;
    }
}
