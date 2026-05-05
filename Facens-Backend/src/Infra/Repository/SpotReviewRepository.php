<?php

namespace App\Infra\Repository;

use App\Domain\Entity\SpotReview;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\SpotReviewRepositoryContract;
use App\Infra\Database\EntitiesOrm\SpotReview as SpotReviewORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;

class SpotReviewRepository extends BaseRepository implements SpotReviewRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(SpotReviewORM::class)
        );
    }

    // ✅ CREATE
    public function create(SpotReview $review): SpotReview
    {
        $reviewOrm = SpotReviewORM::fromDomain($review);

        return $this->persist($reviewOrm)->toDomain();
    }

    // ✅ GET BY ID
    public function getById(int $id): SpotReview
    {
        return parent::getEntityById($id)->toDomain();
    }

    // ✅ UPDATE
    public function update(SpotReview $review): SpotReview
    {
        $reviewOrm = parent::getEntityById($review->getId());

        if (!empty($review->getRating())) {
            $reviewOrm->rating = $review->getRating();
        }

        $reviewOrm->comment = $review->getComment();

        return $this->persist($reviewOrm)->toDomain();
    }

    // ✅ DELETE (opcional, mas útil)
    public function delete(int $id): void
    {
        $reviewOrm = parent::getEntityById($id);
        $this->entityManager->remove($reviewOrm);
        $this->entityManager->flush();
    }

    // ✅ FIND ONE (ex: evitar duplicado)
    public function findOneBy(array $params): ?SpotReview
    {
        $reviewOrm = $this->repository->findOneBy($params);

        if ($reviewOrm === null) {
            return null;
        }

        return $reviewOrm->toDomain();
    }

    // ✅ LISTAR REVIEWS POR SPOT
    public function findBySpot(int $spotId): array
    {
        $reviews = $this->repository->findBy(
            ['spot' => $spotId],
            ['createdAt' => 'DESC']
        );

        return array_map(fn($r) => $r->toDomain(), $reviews);
    }

    // ✅ MÉDIA DE AVALIAÇÃO ⭐
    public function getAverageRatingBySpot(int $spotId): float
    {
        $qb = $this->entityManager->createQueryBuilder();

        $result = $qb
            ->select('AVG(r.rating) as avg')
            ->from(SpotReviewORM::class, 'r')
            ->where('r.spot = :spotId')
            ->setParameter('spotId', $spotId)
            ->getQuery()
            ->getSingleScalarResult();

        return round((float)$result, 1);
    }

    // ✅ PAGINAÇÃO (igual Employee)
    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = $params;

        if ($limit > 0 && $offset >= 0) {
            return new PaginatedEntities(
                totalItems: $this->repository->count($criteria),
                items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
            );
        }

        return new PaginatedEntities(
            totalItems: $this->repository->count($criteria),
            items: $this->getByParams(params: $criteria)
        );
    }

    // ✅ SEARCH AVANÇADO (opcional)
    public function search(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();

        if (!empty($params['spotId'])) {
            $criteria->andWhere(Criteria::expr()->eq('spot', $params['spotId']));
        }

        if (!empty($params['driverId'])) {
            $criteria->andWhere(Criteria::expr()->eq('driver', $params['driverId']));
        }

        if (!empty($params['rating'])) {
            $criteria->andWhere(Criteria::expr()->eq('rating', $params['rating']));
        }

        $totalItems = $this->repository->matching($criteria)->count();

        if ($limit !== null) {
            $criteria->setMaxResults($limit);
        }

        if ($offset !== null) {
            $criteria->setFirstResult($offset);
        }

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $this->repository
                ->matching($criteria)
                ->map(fn ($entity) => $entity->toDomain())
                ->toArray()
        );
    }
}