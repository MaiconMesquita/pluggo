<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\Driver;
use App\Domain\Entity\PaginatedEntities;

interface DriverRepositoryContract
{
    public function create(Driver $driver): Driver;

    public function getById(int $id): Driver;

    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities;

    public function update(Driver $driver): Driver;

    public function findOneBy(array $params): ?Driver;

    public function searchDrivers(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities;
}
