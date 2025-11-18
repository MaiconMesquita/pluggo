<?php

namespace App\Domain\RepositoryContract;
use App\Domain\Entity\Host;
use App\Domain\Entity\PaginatedEntities;

interface HostRepositoryContract
{
    public function create(Host $driver): Host;

    public function getById(int $id): Host;

    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities;

    public function update(Host $driver): Host;

    public function findOneBy(array $params): ?Host;

    public function searchHost(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities;
}
