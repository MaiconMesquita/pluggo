<?php

namespace App\Application\UseCase\DeleteDriver;

use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class DeleteDriver
{
    private DriverRepositoryContract $driverRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->driverRepository = $repositoryFactory->getDriverRepository();
    }

    public function execute(DeleteDriverInput $input): void
    {
        $driver = $this->driverRepository->getById($input->id);

        if (!$driver) {
            throw new InvalidDataException("Driver not found.");
        }

        $this->driverRepository->delete($input->id);
    }
}
