<?php

namespace App\Infra\Repository;

use App\Domain\Entity\PaginatedEntities;
use App\Domain\Exception\NotFoundException;
use App\Infra\Database\EntitiesOrm\BaseOrm;
use Doctrine\ORM\{EntityRepository, EntityManagerInterface};

abstract class BaseRepository
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected EntityRepository $repository
    ) {
    }

    public function getAll(int $limit, int $offset, array $params = [], ?array $orderBy = null): array
    {
        $entities = $this->repository->findBy(
            $params,
            $orderBy ?? ['id' => 'DESC'],
            $limit,
            $offset * $limit
        );
        return array_map(function ($entity) {
            return $entity->toDomain();
        },  $entities);
    }

    public function getEntityById(mixed $id)
    {
        $entity = $this->repository->find($id);

        if (!$entity)
            $this->throwNotFoundException();
        return $entity;
    }

    protected function throwNotFoundException(): void
    {
        throw new NotFoundException(
            substr(strrchr($this->repository->getClassName(), '\\'), 1) . " not found"
        );
    }
    public function getOneByParams(
        array $params,
        ?array $orderBy = null,
    ) {
        $entity =  $this->repository->findOneBy($params, $orderBy);
        if (!$entity)
            $this->throwNotFoundException();
        return $entity;
    }

    public function getByParams(
        array $params,
        ?array $orderBy = null,
        ?int $limit     = null,
        ?int $offset    = null
    ): array {
        return $this->repository->findBy(
            $params,
            $orderBy,
            $limit,
            $offset
        );
    }

    public function persist(BaseOrm $entity)
    {
        if (!$this->entityManager->contains($entity))
            $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function delete(int $id): void
    {
        $entity = $this->repository->find($id);
        if (!$entity)
            $this->throwNotFoundException();

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count($params),
            items: $this->getAll(limit: $limit, offset: $offset, params: $params)
        );
    }
}
