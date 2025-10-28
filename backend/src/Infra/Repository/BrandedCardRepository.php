<?php

namespace App\Infra\Repository;

use App\Domain\Entity\BrandedCard;
use App\Domain\Exception\NotFoundException;
use App\Domain\RepositoryContract\BrandedCardRepositoryContract;
use App\Infra\Database\EntitiesOrm\BrandedCard as BrandedCardORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class BrandedCardRepository extends BaseRepository implements BrandedCardRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(BrandedCardORM::class)
        );
    }

    public function create(BrandedCard $brandedCard): BrandedCard
    {
        $brandedCardOrm = BrandedCardORM::fromDomain($brandedCard);

        return $this->persist($brandedCardOrm)->toDomain();
    }

    public function update(BrandedCard $brandedCard): BrandedCard
    {
        /** @var BrandedCardORM $brandedCardOrm */
        $brandedCardOrm = parent::getEntityById($brandedCard->getId());

        $brandedCardOrm->cardType = $brandedCard->getCardType();
        $brandedCardOrm->productType = $brandedCard->getProductType();
        $brandedCardOrm->isActive = $brandedCard->getIsActive();
        $brandedCardOrm->requestOriginId = $brandedCard->getRequestOriginId();
        $brandedCardOrm->issuerId = $brandedCard->getIssuerId();
        //$brandedCardOrm->statusDescription = $brandedCard->getStatusDescription();
        $brandedCardOrm->embossingName = $brandedCard->getEmbossingName();
        $brandedCardOrm->lastFourDigits = $brandedCard->getCardNumber() ? substr($brandedCard->getCardNumber(), -4) : null;
        //$brandedCardOrm->expirationDate = $brandedCard->getExpirationDate();

        return $this->persist($brandedCardOrm)->toDomain();
    }

    public function getById(string $id, bool $loadRelationships = false): BrandedCard
    {
        /** @var BrandedCardORM $entity */
        $entity = parent::getEntityById($id);

        if (!$entity) {
            throw new NotFoundException("BrandedCard not found with ID: $id");
        }

        if ($loadRelationships) {
            $this->loadRelationships($entity);
        }

        return $entity->toDomain();
    }

    public function getByIssuerId(string $issuerId, bool $loadRelationships = false): BrandedCard
    {
        /** @var BrandedCardORM $entity */
        $entity = $this->repository->findOneBy(['issuerId' => $issuerId]);

        if (!$entity) {
            throw new NotFoundException("BrandedCard not found with Issuer ID: $issuerId");
        }

        if ($loadRelationships) {
            $this->loadRelationships($entity);
        }

        return $entity->toDomain();
    }

    public function getByUserId(int $userId, bool $loadRelationships = false): array
    {
        $queryBuilder = $this->repository->createQueryBuilder('bc')
            ->innerJoin('bc.brandedCardUser', 'bcu')
            ->innerJoin('bcu.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        $entities = $queryBuilder->getQuery()->getResult();

        if ($loadRelationships) {
            foreach ($entities as $entity) {
                $this->loadRelationships($entity);
            }
        }

        return array_map(fn($entity) => $entity->toDomain(), $entities);
    }

    public function getByBrandedCardUserId(int $brandedCardUserId, bool $loadRelationships = false): array
    {
        $entities = $this->repository->findBy(['brandedCardUser' => $brandedCardUserId]);

        if ($loadRelationships) {
            foreach ($entities as $entity) {
                $this->loadRelationships($entity);
            }
        }

        return array_map(fn($entity) => $entity->toDomain(), $entities);
    }

    private function loadRelationships(BrandedCardORM $entity): void
    {
        // Initialize brandedCardUser relationship if it exists
        if (isset($entity->brandedCardUser) && $entity->brandedCardUser instanceof PersistentCollection) {
            $entity->brandedCardUser->initialize();
        } elseif (isset($entity->brandedCardUser)) {
            // Force load by accessing a property
            $entity->brandedCardUser->id;
        }
    }

    public function list(int $limit = 20, int $offset = 0): array
    {
        $entities = $this->repository
            ->createQueryBuilder('cb')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('cb.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(fn($entity) => $entity->toDomain(), $entities);
    }
}
