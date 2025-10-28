<?php

namespace App\Infra\Repository;

use App\Domain\Entity\DiscountRate;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\DiscountRateRepositoryContract;
use App\Infra\Database\EntitiesOrm\{DiscountRate as DiscountRateORM};
use Doctrine\ORM\EntityManagerInterface;

class DiscountRateRepository extends BaseRepository implements DiscountRateRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(DiscountRateORM::class)
        );
    }
    
    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count([]),
            items: $this->getAll(limit: $limit, offset: $offset)
        );
    }

    public function findOneBy(array $params): ?DiscountRate
    {
        $discountRateOrm = $this->repository->findOneBy(
            $params
        );

        if ($discountRateOrm === null) {
            return null;
        }

        return $discountRateOrm->toDomain();
    }

    public function findByTypeAndInstallments(string $type, int $maxInstallment): array
    {
        $queryBuilder = $this->repository->createQueryBuilder('dr');

        $queryBuilder
            ->select('dr.percentageRate') // Seleciona apenas o campo percentageRate
            ->where('dr.type = :type')
            ->andWhere('dr.installment BETWEEN :minInstallment AND :maxInstallment')
            ->setParameters([
                'type' => $type,
                'minInstallment' => 1,
                'maxInstallment' => $maxInstallment,
            ])
            ->orderBy('dr.percentageRate', 'ASC'); // Ordena pelo campo percentageRate em ordem crescente

        $results = $queryBuilder->getQuery()->getResult();

        // Extrai os valores do campo percentageRate para um array de floats
        return array_map(
            fn($result) => (float) $result['percentageRate'],
            $results
        );
    }

    public function findRatesByTypeAndInstallments(array $types, int $maxInstallment): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('dr.installment', 'dr.averagePercentageRate', 'dr.percentageRate', 'dr.termInDay', 'dr.type')
            ->from(DiscountRateORM::class, 'dr')
            ->where('dr.type IN (:types)')
            ->andWhere('dr.installment BETWEEN :minInstallment AND :maxInstallment')
            ->setParameters([
                'types' => $types,
                'minInstallment' => 1,
                'maxInstallment' => $maxInstallment,
            ])
            ->orderBy('dr.installment', 'ASC');

        $results = $queryBuilder->getQuery()->getResult();

        // Organiza os resultados no formato desejado
        $groupedResults = [];
        foreach ($results as $result) {
            $installment = $result['installment'];
            $type = $result['type'];

            if (!isset($groupedResults[$installment])) {
                $groupedResults[$installment] = [];
            }

            // Adiciona os campos dinamicamente no formato desejado
            $groupedResults[$installment][$type . 'PercentageRate'] = $result['percentageRate'];
            $groupedResults[$installment][$type . 'AveragePercentageRate'] = $result['averagePercentageRate'];
            $groupedResults[$installment][$type . 'TermInDay'] = $result['termInDay'];
        }

        return $groupedResults;
    }
}
