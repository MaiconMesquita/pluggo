<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\EntityAggregateSummaryRepositoryContract;
use App\Domain\Entity\EntityAggregateSummary;
use App\Domain\Entity\PaginatedEntities;
use App\Infra\Database\EntitiesOrm\EntityAggregateSummary as EntityAggregateSummaryORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class EntityAggregateSummaryRepository extends BaseRepository implements EntityAggregateSummaryRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(EntityAggregateSummaryORM::class)
        );
    }

    public function create(EntityAggregateSummary $summary): EntityAggregateSummary
    {
        $orm = EntityAggregateSummaryORM::fromDomain($summary);
        return $this->persist($orm)->toDomain();
    }

    public function getById(int $id): EntityAggregateSummary
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getByEntity(int|string|null $entityId, string $entityType): ?EntityAggregateSummary
    {
        $criteria = [
            'entityId'   => $entityId,
            'entityType' => $entityType,
        ];

        $orm = $this->repository->findOneBy($criteria);

        return $orm?->toDomain();
    }

    public function incrementSummary(
        int|string|null $entityId,
        string $entityType,
        array $values
    ): EntityAggregateSummary {
        // Primeiro tenta buscar o summary existente
        $criteria = [
            'entityId'   => $entityId,
            'entityType' => $entityType,
        ];

        /** @var EntityAggregateSummaryORM|null $orm */
        $orm = $this->repository->findOneBy($criteria);

        if (!$orm) {
            $summary = EntityAggregateSummary::create($entityId, $entityType);

            $orm = EntityAggregateSummaryORM::fromDomain($summary);
        }

        // Incrementa os campos dinamicamente
        foreach ($values as $field => $increment) {
            if (property_exists($orm, $field)) {
                $orm->$field += (float) $increment;
            } else {
            }
        }


        $orm->lastUpdatedAt = new \DateTime();

        return $this->persist($orm)->toDomain();
    }


    public function decrementSummary(
        int|string|null $entityId,
        string $entityType,
        array $values
    ): ?EntityAggregateSummary {
        $criteria = [
            'entityId'   => $entityId,
            'entityType' => $entityType,
        ];

        /** @var EntityAggregateSummaryORM|null $orm */
        $orm = $this->repository->findOneBy($criteria);

        // Se não encontrou, retorna null (não faz nada)
        if (!$orm) {
            return null;
        }

        // Decrementa os campos dinamicamente sem deixar negativo
        foreach ($values as $field => $decrement) {
            if (property_exists($orm, $field)) {
                $currentValue = (float) $orm->$field;
                $orm->$field = max($currentValue - (float) $decrement, 0);
            }
        }

        $orm->lastUpdatedAt = new \DateTime();

        return $this->persist($orm)->toDomain();
    }



    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = $params;

        return new PaginatedEntities(
            totalItems: $this->repository->count($criteria),
            items: $limit > 0 && $offset >= 0
                ? $this->getAll(params: $criteria, limit: $limit, offset: $offset)
                : $this->getByParams(params: $criteria)
        );
    }

    public function update(EntityAggregateSummary $summary): EntityAggregateSummary
    {
        $orm = parent::getEntityById($summary->getId());

        $orm->entityId = $summary->getEntityId();
        $orm->entityType = $summary->getEntityType();
        $orm->grossAmount = $summary->getGrossAmount();
        $orm->systemRevenue = $summary->getSystemRevenue();
        $orm->cashbackAmount = $summary->getCashbackAmount();
        $orm->establishmentRevenue = $summary->getEstablishmentRevenue();
        $orm->captureFee = $summary->getCaptureFee();
        $orm->proRataFee = $summary->getProRataFee();
        $orm->multaFee = $summary->getMultaFee();
        $orm->cardFee = $summary->getCardFee();
        $orm->cardCancelationFee = $summary->getCardCancelationFee();
        $orm->anticipationFee = $summary->getAnticipationFee();
        $orm->transactionCount = $summary->getTransactionCount();
        $orm->installmentCount = $summary->getInstallmentCount();
        $orm->lastUpdatedAt = $summary->getLastUpdatedAt();

        return $this->persist($orm)->toDomain();
    }

    public function findOneBy(array $params): ?EntityAggregateSummary
    {
        $orm = $this->repository->findOneBy($params);
        return $orm?->toDomain();
    }

    public function search(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();

        if (!empty($params['entityId'])) {
            $criteria->andWhere(Criteria::expr()->eq('entityId', $params['entityId']));
        }

        if (!empty($params['entityType'])) {
            $criteria->andWhere(Criteria::expr()->eq('entityType', $params['entityType']));
        }

        $totalItems = $this->repository->matching($criteria)->count();

        if ($limit !== null) $criteria->setMaxResults($limit);
        if ($offset !== null) $criteria->setFirstResult($offset);

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $this->repository->matching($criteria)->map(fn($entity) => $entity->toDomain())->toArray()
        );
    }
}
