<?php

namespace App\Infra\Repository;

use App\Domain\Entity\BrandedCard;
use App\Domain\RepositoryContract\CardBrandedRepositoryContract;
use App\Infra\Database\EntitiesOrm\BrandedCard as BrandedCardORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class CardBrandedRepository extends BaseRepository implements CardBrandedRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(BrandedCardORM::class)
        );
    }

    public function create(BrandedCard $cardBranded): BrandedCard
    {
        $cardBrandedOrm = BrandedCardORM::fromDomain($cardBranded);

        return $this->persist($cardBrandedOrm)->toDomain();
    }

    public function update(BrandedCard $cardBranded): BrandedCard
    {
        /** @var BrandedCardORM $cardBrandedOrm */
        $cardBrandedOrm = parent::getEntityById($cardBranded->getId());

        $cardBrandedOrm->cardType = $cardBranded->getCardType();
        $cardBrandedOrm->productType = $cardBranded->getProductType();
        $cardBrandedOrm->isActive = $cardBranded->getIsActive();
        $cardBrandedOrm->statusDescription = $cardBranded->getStatusDescription();
        $cardBrandedOrm->embossingName = $cardBranded->getEmbossingName();
        $cardBrandedOrm->lastFourDigits = $cardBranded->getCardNumber() ? substr($cardBranded->getCardNumber(), -4) : null;
        $cardBrandedOrm->expirationDate = $cardBranded->getExpirationDate();

        return $this->persist($cardBrandedOrm)->toDomain();
    }

    public function getById(string $id, bool $loadRelationships = false): ?BrandedCard
    {
        /** @var BrandedCardORM $entity */
        $entity = parent::getEntityById($id);

        if ($entity && $loadRelationships) {
            // Initialize brandedCardHolder relationship if needed
            if (isset($entity->brandedCardHolder)) {
                // Force load the relationship by accessing a property
                $entity->brandedCardHolder->id;
            }
        }

        return $entity ? $entity->toDomain() : null;
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
