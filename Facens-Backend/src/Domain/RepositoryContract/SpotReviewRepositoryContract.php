<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\SpotReview;
use App\Domain\Entity\PaginatedEntities;

interface SpotReviewRepositoryContract
{
    public function create(SpotReview $review): SpotReview;

    public function update(SpotReview $review): SpotReview;

    public function delete(int $id): void;

    public function getById(int $id): SpotReview;

    public function findOneBy(array $params): ?SpotReview;

    public function findBySpot(int $spotId): array;

    public function getAverageRatingBySpot(int $spotId): float;

    public function getAllPaginated(
        ?int $limit = null,
        ?int $offset = null,
        ?array $params = []
    ): PaginatedEntities;

    public function search(
        ?int $limit = null,
        ?int $offset = null,
        ?array $params = []
    ): PaginatedEntities;
}