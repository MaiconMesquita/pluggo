<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Card;
use App\Domain\Entity\DTO\CardWithSegmentDTO;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\CardRepositoryContract;
use App\Infra\Database\EntitiesOrm\{Card as CardORM, CnaeMcc};
use Doctrine\ORM\EntityManagerInterface;

class CardRepository extends BaseRepository implements CardRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(CardORM::class)
        );
    }

    public function create(Card $card): Card
    {

        $cardOrm = CardORM::fromDomain($card);

        return $this->persist($cardOrm)->toDomain();
    }

    public function update(Card $card): Card
    {
        $cardOrm = parent::getEntityById($card->getId());

        $cardOrm->status = $card->getStatus();
        $cardOrm->creditLimit = $card->getCreditLimit();
        $cardOrm->debitLimit = $card->getDebitLimit();
        $cardOrm->externalCardId = $card->getExternalCardId();

        return $this->persist($cardOrm)->toDomain();
    }

    public function getById(string $id): Card
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getCardsWithSegments(
        int $limit = 20,
        int $offset = 0,
        ?bool $isPrimaryCard = null,
        ?int $primaryCardId = null,
        ?int $segmentId = null,
        ?int $establishmentId = null,
    ): PaginatedEntities {
        $criteria = [];
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('card', 'segment')
            ->from(CardORM::class, 'card')
            ->leftJoin('card.segment', 'segment')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Aplica os filtros dinamicamente
        if (!is_null($isPrimaryCard)) {
            $qb->andWhere('card.isPrimaryCard = :isPrimaryCard')
                ->setParameter('isPrimaryCard', $isPrimaryCard);
            $criteria['isPrimaryCard'] = $isPrimaryCard;
        }

        if (!is_null($primaryCardId)) {
            $qb->andWhere('card.primaryCardId = :primaryCardId')
                ->setParameter('primaryCardId', $primaryCardId);
            $criteria['primaryCardId'] = $primaryCardId;
        }

        if (!is_null($segmentId)) {
            $qb->andWhere('card.segmentId = :segmentId')
                ->setParameter('segmentId', $segmentId);
            $criteria['segmentId'] = $segmentId;
        }

        if (!is_null($establishmentId)) {
            $qb->andWhere('card.establishmentId = :establishmentId')
                ->setParameter('establishmentId', $establishmentId);
            $criteria['establishmentId'] = $establishmentId;
        }

        $results = $qb->getQuery()->getArrayResult();

        $cards = array_map(function (array $row) {
            $segment = $row['segment'];

            return new CardWithSegmentDTO(
                cardId: $row['id'],
                primaryCardId: $row['primaryCardId'],
                isPrimaryCard: $row['isPrimaryCard'],
                segmentId: $segment['id'],
                segmentDescription: $segment['description'],
                segmentColorCode: $segment['colorCode'] ?? null,
                segmentStatus: $segment['status'],
                establishmentId: $row['establishmentId'],
                creditLimit: $row['creditLimit'],
                debitLimit: $row['debitLimit'],
            );
        }, $results);

        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $criteria),
            items: $cards
        );
    }

    public function getCardsByCnaeMccId(
        int $cnaeMccId,
        int $limit = 20,
        int $offset = 0
    ): PaginatedEntities {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('card', 'segment')
            ->from(CardORM::class, 'card')
            ->leftJoin('card.segment', 'segment')
            ->innerJoin(CnaeMcc::class, 'cnaeMcc', 'WITH', 'segment.id = cnaeMcc.segmentId')
            ->where('cnaeMcc.id = :cnaeMccId')
            ->setParameter('cnaeMccId', $cnaeMccId)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $results = $qb->getQuery()->getArrayResult();

        $cards = array_map(function (array $row) {
            $segment = $row['segment'];

            return new CardWithSegmentDTO(
                cardId: $row['id'],
                primaryCardId: $row['primaryCardId'],
                isPrimaryCard: $row['isPrimaryCard'],
                segmentId: $segment['id'],
                segmentDescription: $segment['description'],
                segmentColorCode: $segment['colorCode'] ?? null,
                segmentStatus: $segment['status'],
                establishmentId: $row['establishmentId'],
                creditLimit: $row['creditLimit'],
                debitLimit: $row['debitLimit'],
            );
        }, $results);

        return new PaginatedEntities(
            totalItems: count($cards),
            items: $cards
        );
    }

    public function getCardByCnaeMccId(int $cnaeMccId): ?Card
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('card')
            ->from(CardORM::class, 'card')
            ->innerJoin(CnaeMcc::class, 'cnaeMcc', 'WITH', 'card.segmentId = cnaeMcc.segmentId')
            ->where('cnaeMcc.id = :cnaeMccId')
            ->setParameter('cnaeMccId', $cnaeMccId)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result?->toDomain();
    }
}
