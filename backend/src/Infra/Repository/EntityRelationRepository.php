<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\EntityRelationRepositoryContract;
use App\Domain\Entity\EntityRelation;
use App\Domain\Entity\PaginatedEntities;
use App\Infra\Database\EntitiesOrm\EntityRelation as EntityRelationORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class EntityRelationRepository extends BaseRepository implements EntityRelationRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(EntityRelationORM::class)
        );
    }

    public function create(EntityRelation $entityRelation): EntityRelation
    {
        $orm = EntityRelationORM::fromDomain($entityRelation);
        return $this->persist($orm)->toDomain();
    }

    public function getById(int $id): EntityRelation
    {
        return parent::getEntityById($id)->toDomain();
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

    public function update(EntityRelation $entityRelation): EntityRelation
    {
        $orm = parent::getEntityById($entityRelation->getId());

        if (!empty($entityRelation->getChildId())) $orm->childId = $entityRelation->getChildId();
        if (!empty($entityRelation->getChildType())) $orm->childType = $entityRelation->getChildType();
        if (!empty($entityRelation->getParentId())) $orm->parentId = $entityRelation->getParentId();
        if (!empty($entityRelation->getParentType())) $orm->parentType = $entityRelation->getParentType();
        if (!empty($entityRelation->getRelationType())) $orm->relationType = $entityRelation->getRelationType();

        return $this->persist($orm)->toDomain();
    }

    public function findBy(array $filters = [], ?int $limit = null, ?int $offset = null): PaginatedEntities
    {
        $criteria = Criteria::create();

        if (!empty($filters['parentId'])) {
            $criteria->andWhere(Criteria::expr()->eq('parentId', $filters['parentId']));
        }

        if (!empty($filters['parentType'])) {
            $criteria->andWhere(Criteria::expr()->eq('parentType', $filters['parentType']));
        }

        if (!empty($filters['childId'])) {
            $criteria->andWhere(Criteria::expr()->eq('childId', $filters['childId']));
        }

        if (!empty($filters['childType'])) {
            $criteria->andWhere(Criteria::expr()->eq('childType', $filters['childType']));
        }

        if (!empty($filters['relationType'])) {
            $criteria->andWhere(Criteria::expr()->eq('relationType', $filters['relationType']));
        }

        $totalItems = $this->repository->matching($criteria)->count();

        if ($limit !== null) $criteria->setMaxResults($limit);
        if ($offset !== null) $criteria->setFirstResult($offset);

        $items = $this->repository->matching($criteria)
            ->map(fn($entity) => $entity->toDomain())
            ->toArray();

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $items
        );
    }


    public function findOneBy(array $params): ?EntityRelation
    {
        $orm = $this->repository->findOneBy($params);
        return $orm?->toDomain();
    }



    public function getByParent(int $parentId, string $parentType, ?int $limit = null, ?int $offset = null): PaginatedEntities
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('parentId', $parentId))
            ->andWhere(Criteria::expr()->eq('parentType', $parentType));


        $totalItems = $this->repository->matching($criteria)->count();

        if ($limit !== null) {
            $criteria->setMaxResults($limit);
        }
        if ($offset !== null) {
            $criteria->setFirstResult($offset);
        }

        $items = $this->repository
            ->matching($criteria)
            ->map(fn($entity) => $entity->toDomain())
            ->toArray();

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $items
        );
    }

    public function getByChild(int $childId, string $childType): array
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('childId', $childId))
            ->andWhere(Criteria::expr()->eq('childType', $childType));

        return $this->repository
            ->matching($criteria)
            ->map(fn($entity) => $entity->toDomain())
            ->toArray();
    }


    public function search(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();

        if (!empty($params['childId'])) {
            $criteria->andWhere(Criteria::expr()->eq('childId', $params['childId']));
        }

        if (!empty($params['childType'])) {
            $criteria->andWhere(Criteria::expr()->eq('childType', $params['childType']));
        }

        if (!empty($params['parentId'])) {
            $criteria->andWhere(Criteria::expr()->eq('parentId', $params['parentId']));
        }

        if (!empty($params['parentType'])) {
            $criteria->andWhere(Criteria::expr()->eq('parentType', $params['parentType']));
        }

        if (!empty($params['relationType'])) {
            $criteria->andWhere(Criteria::expr()->eq('relationType', $params['relationType']));
        }

        $totalItems = $this->repository->matching($criteria)->count();

        if ($limit !== null) $criteria->setMaxResults($limit);
        if ($offset !== null) $criteria->setFirstResult($offset);

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $this->repository->matching($criteria)
                ->map(fn($entity) => $entity->toDomain())
                ->toArray()
        );
    }
}
