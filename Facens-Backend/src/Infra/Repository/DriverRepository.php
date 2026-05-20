<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\Entity\Driver;
use App\Domain\Entity\PaginatedEntities;
use App\Infra\Database\EntitiesOrm\{Driver as DriverORM};
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class DriverRepository extends BaseRepository implements DriverRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(DriverORM::class)
        );
    }

    public function create(Driver $driver): Driver
    {
        $DriverOrm = DriverORM::fromDomain($driver);

        return $this->persist($DriverOrm)->toDomain();
    }

    public function getById(int $id): Driver
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = $params;
        // Filtrar por múltiplos ids, se fornecido
        if (!empty($params['ids']) && is_array($params['ids'])) {
            $criteria['id'] = $params['ids'];
            unset($criteria['ids']); // Remove o parâmetro customizado
        }

        if ($limit > 0 && $offset >= 0)
            return new PaginatedEntities(
                totalItems: $this->repository->count($criteria),
                items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
            );
        else
            return new PaginatedEntities(
                totalItems: $this->repository->count($criteria),
                items: $this->getByParams(params: $criteria)
            );
    }

    public function update(Driver $driver): Driver
    {

        $DriverOrm = parent::getEntityById($driver->getId());

        if (!empty($driver->getName())) $DriverOrm->name = $driver->getName();
        if (!empty($driver->getPhone())) $DriverOrm->phone = (string) $driver->getPhone();
        if (!empty($driver->getEmail())) $DriverOrm->email = $driver->getEmail();
        if (!empty($driver->getPassword())) $DriverOrm->password = $driver->getPassword();

        return $this->persist($DriverOrm)->toDomain();
    }

    public function findOneBy(array $params): ?Driver
    {

        $employeeOrm = $this->repository->findOneBy(
            $params
        );

        if ($employeeOrm === null) {
            return null;
        }

        return $employeeOrm->toDomain();
    }

    public function searchDrivers(?int $limit = null, ?int $offset = null, ?array $filters = []): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('b')
            ->from(DriverORM::class, 'b')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('b.createdAt', 'DESC');

        $countQb = $this->entityManager->createQueryBuilder();
        $countQb->select('COUNT(b.id)')
            ->from(DriverORM::class, 'b');

        /**
         * 🔹 CAMPOS PERMITIDOS (evita bug + segurança)
         */
        $allowedFields = [
            'email',
            'name',
            'phone',
            'id', // só se existir no ORM
        ];

        foreach (($filters ?? []) as $field => $value) {

            if ($value === null || $value === '') {
                continue;
            }

            if (!in_array($field, $allowedFields)) {
                continue;
            }

            $param = "{$field}Param";

            // filtro parcial para name/email
            if (in_array($field, ['name', 'email'])) {

                $qb->andWhere("b.$field LIKE :$param");
                $countQb->andWhere("b.$field LIKE :$param");

                $qb->setParameter($param, "%$value%");
                $countQb->setParameter($param, "%$value%");

                continue;
            }

            // filtro exato pros outros
            $qb->andWhere("b.$field = :$param");
            $countQb->andWhere("b.$field = :$param");

            $qb->setParameter($param, $value);
            $countQb->setParameter($param, $value);
        }

        /**
         * 🔹 TOTAL
         */
        $totalItems = (int) $countQb->getQuery()->getSingleScalarResult();

        /**
         * 🔹 RESULTADOS
         */
        $items = array_map(
            fn(DriverORM $orm) => $orm->toDomain(),
            $qb->getQuery()->getResult()
        );

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $items
        );
    }

    public function delete(int $id): void
    {
        parent::delete($id);
    }
}
