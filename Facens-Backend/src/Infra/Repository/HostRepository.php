<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Domain\Entity\Host;
use App\Domain\Entity\PaginatedEntities;
use App\Infra\Database\EntitiesOrm\{Host as HostORM};
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class HostRepository extends BaseRepository implements HostRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(HostORM::class)
        );
    }

    public function create(Host $host): Host
    {
        $hostORM = HostORM::fromDomain($host);

        return $this->persist($hostORM)->toDomain();
    }

    public function getById(int $id): Host
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function delete(int $id): void
    {
        parent::delete($id);
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

    public function update(Host $host): Host
    {

        $HostOrm = parent::getEntityById($host->getId());

        if (!empty($host->getName())) $HostOrm->name = $host->getName();
        if (!empty($host->getPhone())) $HostOrm->phone = (string) $host->getPhone();
        if (!empty($host->getEmail())) $HostOrm->email = $host->getEmail();
        if (!empty($host->getPassword())) $HostOrm->password = $host->getPassword();

        return $this->persist($HostOrm)->toDomain();
    }

    public function findOneBy(array $params): ?Host
    {

        $employeeOrm = $this->repository->findOneBy(
            $params
        );

        if ($employeeOrm === null) {
            return null;
        }

        return $employeeOrm->toDomain();
    }

    public function searchHost(?int $limit = null, ?int $offset = null, ?array $filters = []): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('b')
            ->from(HostORM::class, 'b')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('b.createdAt', 'DESC');

        $countQb = $this->entityManager->createQueryBuilder();
        $countQb->select('COUNT(b.id)')
            ->from(HostORM::class, 'b');

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
            fn(HostORM $orm) => $orm->toDomain(),
            $qb->getQuery()->getResult()
        );

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $items
        );
    }
}
