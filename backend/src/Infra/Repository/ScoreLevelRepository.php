<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\ScoreLevelRepositoryContract;
use App\Domain\Entity\ScoreLevel;
use App\Infra\Database\EntitiesOrm\ScoreLevel as ScoreLevelORM;
use Doctrine\ORM\EntityManagerInterface;

class ScoreLevelRepository extends BaseRepository implements ScoreLevelRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(ScoreLevelORM::class)
        );
    }

    public function create(ScoreLevel $scoreLevel): ScoreLevel
    {
        $scoreLevelOrm = ScoreLevelORM::fromDomain($scoreLevel);

        return $this->persist($scoreLevelOrm)->toDomain();
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?ScoreLevel
    {
        $entity = $this->repository->findOneBy($criteria, $orderBy);
        return $entity?->toDomain();
    }



    public function getById(string $id): ScoreLevel
    {
        return parent::getEntityById($id)->toDomain();
    }

    /**
     * Retorna todos os níveis de score
     */
    public function getAll(int $limit = 1000, int $offset = 0, array $params = [], ?array $orderBy = null): array
    {
        return parent::getAll($limit, $offset, $params, $orderBy);
    }



    /**
     * Retorna o nível correspondente a um score específico
     */
    public function getByScore(int $score): ?ScoreLevel
    {
        $entity = $this->repository->createQueryBuilder('s')
            ->where('s.scoreMin <= :score')
            ->andWhere('s.scoreMax >= :score')
            ->setParameter('score', $score)
            ->getQuery()
            ->getOneOrNullResult();

        return $entity?->toDomain();
    }
}
