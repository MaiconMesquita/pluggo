<?php

namespace App\Application\UseCase\DeleteChargeSpot;

use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class DeleteChargeSpot
{
    private ChargeSpotsRepositoryContract $spotRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->spotRepository = $repositoryFactory->getChargeSpotsRepository();
    }

    public function execute(DeleteChargeSpotInput $input): void
    {
        $spot = $this->spotRepository->getById($input->id);

        if (!$spot) {
            throw new InvalidDataException("ChargeSpot not found.");
        }

        $this->spotRepository->delete($input->id);
    }
}
