<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\{PaginatedEntities, User, UserDocument};
use App\Domain\Entity\ValueObject\DocumentType;

interface UserRepositoryContract
{
    public function create(User $user): User;

    public function update(User $user): User;

    public function getById(int $id, bool $loadRelationships = false): User;

    public function findOneBy(array $params, bool $loadRelationships = false): ?User;

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities;

    public function searchUsers(int $limit, int $offset, string $filter, string $field, ?bool $support = false): PaginatedEntities;

    public function saveUserDocument(int $userId, UserDocument $document): UserDocument;
}
