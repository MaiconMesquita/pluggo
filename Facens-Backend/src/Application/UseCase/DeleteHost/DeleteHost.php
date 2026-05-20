<?php

namespace App\Application\UseCase\DeleteHost;

use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class DeleteHost
{
    private HostRepositoryContract $hostRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->hostRepository = $repositoryFactory->getHostRepository();
    }

    public function execute(DeleteHostInput $input): void
    {
        $host = $this->hostRepository->getById($input->id);

        if (!$host) {
            throw new InvalidDataException("Host not found.");
        }

        $this->hostRepository->delete($input->id);
    }
}
