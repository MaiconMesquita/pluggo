<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\Employee;
use App\Domain\Entity\PaginatedEntities;

interface EmployeeRepositoryContract
{
    public function create(Employee $employee): Employee;

    public function getById(int $id): Employee;

    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities;

    public function update(Employee $employee): Employee;

    public function findOneBy(array $params): ?Employee;

    public function searchEmployees(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities;
}
