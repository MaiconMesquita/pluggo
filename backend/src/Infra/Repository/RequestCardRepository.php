<?php

namespace App\Infra\Repository;

use App\Domain\Entity\RequestCard;
use App\Domain\RepositoryContract\RequestCardRepositoryContract;
use App\Infra\Database\EntitiesOrm\RequestCard as RequestCardORM;
use Doctrine\ORM\EntityManagerInterface;

class RequestCardRepository extends BaseRepository implements RequestCardRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(RequestCardORM::class)
        );
    }

    /**
     * Cria ou atualiza uma solicitação de cartão.
     */
    public function save(RequestCard $requestCard): RequestCard
    {
        $entity = RequestCardORM::fromDomain($requestCard);
        return $this->persist($entity)->toDomain();
    }

    /**
     * Busca uma solicitação por ID.
     */
    public function findById(int $id): ?RequestCard
    {
        $entity = $this->repository->find($id);
        return $entity?->toDomain();
    }

    /**
     * Busca todas as solicitações de um usuário.
     */
    public function findByUserId(int $userId): array
    {
        $entities = $this->repository->findBy(['userId' => $userId]);
        return array_map(fn($e) => $e->toDomain(), $entities);
    }

    /**
     * Retorna todas as solicitações.
     */
    public function findAll(int $limit = 1000, int $offset = 0, array $params = [], ?array $orderBy = null): array
    {
        return parent::getAll($limit, $offset, $params, $orderBy);
    }

    /**
     * Remove uma solicitação.
     */
    public function delete(int $id): void
    {
        parent::delete($id);
    }
}
