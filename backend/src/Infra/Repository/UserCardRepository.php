<?php

namespace App\Infra\Repository;

use App\Domain\Entity\{UserCard, PaginatedEntities};
use App\Domain\Entity\DTO\UserCardDTO;
use App\Domain\RepositoryContract\UserCardRepositoryContract;
use App\Infra\Database\EntitiesOrm\{User, Card, UserCard as UserCardORM};
use Doctrine\ORM\EntityManagerInterface;

class UserCardRepository extends BaseRepository implements UserCardRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(UserCardORM::class)
        );
    }

    public function create(UserCard $userCard): UserCard
    {
        $userCardOrm = UserCardORM::fromDomain($userCard);

        if ($userCard->getCardId()) {
            $userCardOrm->card = $this->entityManager->getReference(
                Card::class,
                $userCard->getCardId()
            );
        }

        if ($userCard->getUserId()) {
            $userCardOrm->user = $this->entityManager->getReference(
                User::class,
                $userCard->getUserId()
            );
        }

        if ($userCard->getPrimaryUserCardId()) {
            $userCardOrm->primaryUserCard = $this->entityManager->getReference(
                UserCardORM::class,
                $userCard->getPrimaryUserCardId()
            );
        }

        return $this->persist($userCardOrm)->toDomain();
    }

    public function getById(int $id): UserCard
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        return new PaginatedEntities(
            totalItems: $this->repository->count($params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }

    public function countAll(array $params = []): int
    {
        $params['deactivationStatus'] = false;
        return $this->repository->count($params);
    }

    public function findOneBy(array $params): ?UserCard
    {
        $params['deactivationStatus'] = false;
        $userEstablishmentOrm = $this->repository->findOneBy(
            $params
        );

        if ($userEstablishmentOrm === null) {
            return null;
        }

        return $userEstablishmentOrm->toDomain();
    }

    public function update(UserCard $userCard): UserCard
    {
        $userCardOrm = parent::getEntityById($userCard->getId());

        $userCardOrm->numberCard = $userCard->getNumberCard();
        $userCardOrm->invoiceClosingDate = $userCard->getInvoiceClosingDate();
        $userCardOrm->creditBalance = $userCard->getCreditBalance();
        $userCardOrm->debitBalance = $userCard->getDebitBalance();
        $userCardOrm->creditLimit = $userCard->getCreditLimit();
        $userCardOrm->pendingBalance = $userCard->getPendingBalance();
        $userCardOrm->status = $userCard->getStatus();
        $userCardOrm->payStatus = $userCard->getPayStatus();
        $userCardOrm->debitLimit = $userCard->getDebitLimit();
        $userCardOrm->invoiceDueDate = $userCard->getInvoiceDueDate();
        $userCardOrm->deactivationStatus = $userCard->getDeactivationStatus();
        $userCardOrm->deactivationDate = $userCard->getDeactivationDate();
        $userCardOrm->pendingBalanceFee = $userCard->getPendingBalanceFee();

        return $this->persist($userCardOrm)->toDomain();
    }

    public function getAllUserCards(
        ?int $limit = null,
        ?int $offset = null,
        array $params = [],
        ?array $orderBy = null,
    ): array {
        $params['deactivationStatus'] = false;
        return array_map(
            function (UserCardORM $userCard) {
                return $userCard->toDomain();
            },
            $this->repository->findBy($params, $orderBy, $limit, $offset)
        );
    }

    public function getAllWithCardPaginated(
        int $limit,
        int $offset,
        array $params = [],
        ?array $orderBy = null
    ): PaginatedEntities {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('uc', 'c')
            ->from(UserCardORM::class, 'uc')
            ->leftJoin('uc.card', 'c')
            ->where('uc.deactivationStatus = false')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Filtros dinâmicos (params)
        foreach ($params as $field => $value) {
            $paramName = str_replace('.', '_', $field);
            $qb->andWhere("uc.$field = :$paramName")
                ->setParameter($paramName, $value);
        }

        // Ordenação dinâmica
        if ($orderBy) {
            foreach ($orderBy as $field => $direction) {
                $qb->addOrderBy("uc.$field", $direction);
            }
        }

        $results = $qb->getQuery()->getArrayResult();

        // Total de itens para paginação
        $countQb = $this->entityManager->createQueryBuilder();
        $countQb->select('COUNT(uc.id)')
            ->from(UserCardORM::class, 'uc')
            ->where('uc.deactivationStatus = false');

        foreach ($params as $field => $value) {
            $paramName = str_replace('.', '_', $field);
            $countQb->andWhere("uc.$field = :$paramName")
                ->setParameter($paramName, $value);
        }

        $totalItems = (int) $countQb->getQuery()->getSingleScalarResult();

        $userCards = array_map(function (array $row) {
            $card = $row['card'];

            return new UserCardDTO(
                id: $row['id'] ?? null,
                userId: $row['userId'] ?? null,
                cardId: $row['cardId'] ?? null,
                primaryUserCardId: $row['primaryUserCardId'] ?? null,
                isPrimaryUserCard: $row['isPrimaryUserCard'] ?? null,
                invoiceClosingDate: $row['invoiceClosingDate'],
                invoiceDueDate: $row['invoiceDueDate'],
                creditBalance: $row['creditBalance'],
                pendingBalance: $row['pendingBalance'],
                pendingBalanceFee: $row['pendingBalanceFee'],
                debitBalance: $row['debitBalance'],
                creditLimit: $row['creditLimit'],
                debitLimit: $row['debitLimit'],
                status: $row['status'],
                payStatus: $row['payStatus'],
                numberCard: $row['numberCard'],
                deactivationStatus: $row['deactivationStatus'],
                deactivationDate: ($row['deactivationDate'] instanceof \DateTime ? $row['deactivationDate'] : new \DateTime($row['deactivationDate']))->format('Y-m-d H:i:s'),
                createdAt: ($row['createdAt'] instanceof \DateTime ? $row['createdAt'] : new \DateTime($row['createdAt']))->format('Y-m-d H:i:s'),
                updatedAt: ($row['updatedAt'] instanceof \DateTime ? $row['updatedAt'] : new \DateTime($row['updatedAt']))->format('Y-m-d H:i:s'),
                segmentId: $card['segmentId'],
                description: $card['description'] ?? null,
                colorCode: $card['colorCode'] ?? null,
            );
        }, $results);


        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $userCards
        );
    }

}
