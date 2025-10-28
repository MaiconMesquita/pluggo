<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Segment;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\SegmentRepositoryContract;
use App\Infra\Database\EntitiesOrm\{Segment as SegmentORM};
use Doctrine\ORM\EntityManagerInterface;

class SegmentRepository extends BaseRepository implements SegmentRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(SegmentORM::class)
        );
    }
    
    public function getById(int $id): Segment
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function findOneBy(array $params): ?Segment
    {
        $SegmentOrm = $this->repository->findOneBy(
            $params
        );

        if ($SegmentOrm === null) {
            return null;
        }

        return $SegmentOrm->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }
    
    public function countAll(array $params = []): int
    {
        return $this->repository->count($params);
    }
}
