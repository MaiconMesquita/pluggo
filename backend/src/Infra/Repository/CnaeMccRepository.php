<?php

namespace App\Infra\Repository;

use App\Domain\Entity\CnaeMcc;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\CnaeMccRepositoryContract;
use App\Infra\Database\EntitiesOrm\{CnaeMcc as CnaeMccORM};
use Doctrine\ORM\EntityManagerInterface;

class CnaeMccRepository extends BaseRepository implements CnaeMccRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(CnaeMccORM::class)
        );
    }
    
    public function getById(int $id): CnaeMcc
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function findOneBy(array $params): ?CnaeMcc
    {
        $cnaeMccOrm = $this->repository->findOneBy(
            $params
        );

        if ($cnaeMccOrm === null) {
            return null;
        }

        return $cnaeMccOrm->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }
}
