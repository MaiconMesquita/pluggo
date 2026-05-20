<?php

namespace App\Application\UseCase\ListChargeSpots;

use App\Domain\Entity\Auth;
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

    public function execute(ListChargeSpotsInput $input): array
    {
        $auth = Auth::getLogged();
        $authType = $auth->getAuthType();

        if ($authType === 'host') {
            return $this->listForHost($auth);
        } elseif ($authType === 'employee') {
            return $this->listForEmployee($auth, $input);
        } elseif ($authType === 'driver') {
            return $this->listForDriver();
        }

        throw new InvalidDataException("Tipo de autenticação não suportado: $authType");
    }

    private function listForHost(Auth $auth): array
    {
        $hostId = $auth->getHost();

        if (!$hostId) {
            throw new InvalidDataException("Host não identificado no token.");
        }

        $host = $this->hostRepositoryContract->getById($hostId);

        if (!$host) {
            throw new InvalidDataException("Host inválido.");
        }

        return $this->chargeSpotsRepository->getByHostId($host->getId());
    }

    private function listForEmployee(Auth $auth, ListChargeSpotsInput $input): array
    {
        $hostId = $input->hostId;

        if ($hostId === null || $hostId === '') {
            return $this->chargeSpotsRepository->list();
        }

        $host = $this->hostRepositoryContract->getById($hostId);

        if (!$host) {
            throw new InvalidDataException("HostId inválido: $hostId");
        }

        return $this->chargeSpotsRepository->getByHostId($host->getId());
    }

    private function listForDriver(): array
    {
        return $this->chargeSpotsRepository->list();
    }
}
