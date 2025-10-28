<?php

namespace App\Infra\Repository;

use App\Domain\Entity\BaseFees;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\BaseFeesRepositoryContract;
use App\Infra\Database\EntitiesOrm\BaseFeesOrm;
use Doctrine\ORM\EntityManagerInterface;

class BaseFeesRepository extends BaseRepository implements BaseFeesRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(BaseFeesOrm::class)
        );
    }
    
    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count([]),
            items: $this->getAll(limit: $limit, offset: $offset)
        );
    }

    public function findOneBy(array $params): ?BaseFees
    {
        $baseFeesOrm = $this->repository->findOneBy($params);

        if ($baseFeesOrm === null) {
            return null;
        }

        return $baseFeesOrm->toDomain();
    }
}
