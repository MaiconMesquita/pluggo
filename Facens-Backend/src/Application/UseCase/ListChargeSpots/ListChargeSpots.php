<?php

namespace App\Application\UseCase\ListChargeSpots;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListChargeSpots
{
    private HostRepositoryContract $hostRepositoryContract;
    private ChargeSpotsRepositoryContract $chargeSpotsRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->hostRepositoryContract = $repositoryFactory->getHostRepository();
        $this->chargeSpotsRepository = $repositoryFactory->getChargeSpotsRepository();
    }

    public function execute(ListChargeSpotsInput $input)
    {
        $hostId = $input->hostId ?? Auth::getLogged()->getHost();

        $host = $this->hostRepositoryContract->getById($hostId);

        if (!$host) {
            throw new InvalidDataException("HostId inválido para o usuário atual.");
        }

        $spots = $this->chargeSpotsRepository->getByHostId($host->getId());

        return $spots;
    }
}
