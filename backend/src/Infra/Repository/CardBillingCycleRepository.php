<?php

namespace App\Infra\Repository;

use App\Domain\Entity\CardBillingCycle;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\CardBillingCycleRepositoryContract;
use App\Infra\Database\EntitiesOrm\{CardBillingCycle as CardBillingCycleORM};
use Doctrine\ORM\EntityManagerInterface;

class CardBillingCycleRepository extends BaseRepository implements CardBillingCycleRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(CardBillingCycleORM::class)
        );
    }
    
    public function getById(int $id): CardBillingCycle
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function findOneBy(array $params): ?CardBillingCycle
    {
        $cardBillingCycleOrm = $this->repository->findOneBy(
            $params
        );

        if ($cardBillingCycleOrm === null) {
            return null;
        }

        return $cardBillingCycleOrm->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }
}
