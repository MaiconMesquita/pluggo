<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\{PaginatedEntities, UserCard};

interface UserCardRepositoryContract
{
    public function create(UserCard $userCard): UserCard;

    public function getById(int $id): UserCard;

    public function findOneBy(array $params): ?UserCard;

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities;

    public function countAll(array $params = []): int;

    public function update(UserCard $userCard): UserCard;

    public function getAllUserCards(
        ?int $limit = null,
        ?int $offset = null,
        array $params = [],
        ?array $orderBy = null,
    ): array;

    public function getAllWithCardPaginated(
        int $limit,
        int $offset,
        array $params = [],
        ?array $orderBy = null
    ): PaginatedEntities;
}