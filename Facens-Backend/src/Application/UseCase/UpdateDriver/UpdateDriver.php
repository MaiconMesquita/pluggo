<?php

namespace App\Application\UseCase\UpdateDriver;

use App\Domain\Entity\Driver;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class UpdateDriver
{
    private DriverRepositoryContract $driverRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->driverRepository = $repositoryFactory->getDriverRepository();
    }

    public function execute(UpdateDriverInput $input): void
    {
        $driver = $this->driverRepository->getById($input->id);

        if (!$driver) {
            throw new InvalidDataException("Driver not found.");
        }

        if ($input->name !== null) {
            $driver->setName($input->name);
        }
        if ($input->phone !== null) {
            $driver->setPhone($input->phone);
        }
        if ($input->email !== null) {
            $driver->setEmail($input->email);
        }
          

        $this->driverRepository->update($driver);
    }
}