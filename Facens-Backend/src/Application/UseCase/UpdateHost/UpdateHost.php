<?php

namespace App\Application\UseCase\UpdateHost;

use App\Domain\Entity\Host;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class UpdateHost
{
    private HostRepositoryContract $hostRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->hostRepository = $repositoryFactory->getHostRepository();
    }

    public function execute(UpdateHostInput $input): void
    {
        $host = $this->hostRepository->getById($input->id);

        if (!$host) {
            throw new InvalidDataException("Host not found.");
        }

         if ($input->name !== null) {
            $host->setName($input->name);
        }
        if ($input->phone !== null) {
            $host->setPhone($input->phone);
        }
        if ($input->email !== null) {
            $host->setEmail($input->email);
        }

        $this->hostRepository->update($host);
    }
}
