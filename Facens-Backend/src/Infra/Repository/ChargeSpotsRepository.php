<?php

namespace App\Infra\Repository;

use App\Domain\Entity\ChargeSpots;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\Exception\NotFoundException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Infra\Database\EntitiesOrm\ChargeSpots as ChargeSpotsORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class ChargeSpotsRepository extends BaseRepository implements ChargeSpotsRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(ChargeSpotsORM::class)
        );
    }

    public function create(ChargeSpots $spot): ChargeSpots
    {
        $spotOrm = ChargeSpotsORM::fromDomain($spot);

        // Referência para o host
        $hostOrm = $this->entityManager->getReference(
            \App\Infra\Database\EntitiesOrm\Host::class,
            $spot->getHost()->getId()
        );
        $spotOrm->host = $hostOrm;

        return $this->persist($spotOrm)->toDomain();
    }

    public function testFetchAll(): array
    {
        // QueryBuilder simples sem filtros
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('spot')
            ->from(ChargeSpotsORM::class, 'spot');

        $entities = $qb->getQuery()->getResult();

        // Depuração: loga quantos registros vieram
        error_log('Total entities fetched: ' . count($entities));

        // Converte para domínio
        $domains = array_map(fn($entity) => $entity->toDomain(), $entities);

        // Depuração: loga o conteúdo
        foreach ($domains as $i => $domain) {
            error_log("Domain $i: " . print_r($domain->toJSON(), true));
        }

        return $domains;
    }


    public function update(ChargeSpots $spot): ChargeSpots
    {
        /** @var ChargeSpotsORM $spotOrm */
        $spotOrm = parent::getEntityById($spot->getId());

        $spotOrm->latitude = $spot->getLatitude();
        $spotOrm->longitude = $spot->getLongitude();
        $spotOrm->pricePerKwh = $spot->getPricePerKwh();
        $spotOrm->connectorType = $spot->getConnectorType();
        $spotOrm->status = $spot->getStatus();
        $spotOrm->updatedAt = new \DateTime();

        return $this->persist($spotOrm)->toDomain();
    }

    public function getById(int $id, bool $loadRelationships = false): ChargeSpots
    {
        /** @var ChargeSpotsORM $entity */
        $entity = parent::getEntityById($id);

        if (!$entity) {
            throw new NotFoundException("ChargeSpot not found with ID: $id");
        }

        if ($loadRelationships) {
            $this->loadRelationships($entity);
        }

        return $entity->toDomain();
    }

    public function getByHostId(int $hostId, bool $loadRelationships = false): array
    {
        $queryBuilder = $this->repository->createQueryBuilder('spot')
            ->innerJoin('spot.host', 'host')
            ->where('host.id = :hostId')
            ->setParameter('hostId', $hostId);

        $entities = $queryBuilder->getQuery()->getResult();

        if ($loadRelationships) {
            foreach ($entities as $entity) {
                $this->loadRelationships($entity);
            }
        }

        return array_map(fn($entity) => $entity->toDomain(), $entities);
    }

    public function list(int $limit = 20, int $offset = 0): array
    {
        $entities = $this->repository
            ->createQueryBuilder('spot')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('spot.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(fn($entity) => $entity->toDomain(), $entities);
    }

    public function getNearby(float $latitude, float $longitude, float $radiusKm = 5): array
    {
        $delta = $radiusKm / 111;

        $minLat = $latitude - $delta;
        $maxLat = $latitude + $delta;
        $minLng = $longitude - $delta;
        $maxLng = $longitude + $delta;

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('spot')
            ->from(ChargeSpotsORM::class, 'spot')
            ->where('spot.latitude BETWEEN :minLat AND :maxLat')
            ->andWhere('spot.longitude BETWEEN :minLng AND :maxLng')
            ->setParameters([
                'minLat' => $minLat,
                'maxLat' => $maxLat,
                'minLng' => $minLng,
                'maxLng' => $maxLng
            ]);

        return array_map(
            fn($orm) => $orm->toDomain(),
            $qb->getQuery()->getResult()
        );
    }

    private function loadRelationships(ChargeSpotsORM $entity): void
    {
        // Exemplo: inicializar collection se existir
        if (isset($entity->reviews) && $entity->reviews instanceof PersistentCollection) {
            $entity->reviews->initialize();
        }
    }
}
