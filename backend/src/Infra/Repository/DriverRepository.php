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
        
        if($limit > 0 && $offset >= 0)
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

    public function searchDrivers(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();

        // Filtro para apenas registros ativos

        // Filtrar por múltiplos ids, se fornecido
        if (!empty($params['ids']) && is_array($params['ids'])) {
            $criteria->andWhere(Criteria::expr()->in('id', $params['ids']));
        }

        // Aplicar filtro LIKE se 'field' e 'filter' forem fornecidos
        if (!empty($params['field']) && !empty($params['filter'])) {
            $criteria->andWhere(
                Criteria::expr()->contains($params['field'], $params['filter'])
            );
        }

        // Filtrar por employeeType se fornecido
        if (!empty($params['employeeType'])) {
            $criteria->andWhere(Criteria::expr()->eq('employeeType', $params['employeeType']));
        }

        // Filtrar por superiorId se fornecido
        if (!empty($params['superiorId'])) {
            $criteria->andWhere(Criteria::expr()->eq('superiorId', $params['superiorId']));
        }

        // Contar total de itens
        $totalItems = $this->repository->matching($criteria)->count();

        // Aplicar limite e offset
        if ($limit !== null) {
            $criteria->setMaxResults($limit);
        }
        if ($offset !== null) {
            $criteria->setFirstResult($offset);
        }

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $this->repository->matching($criteria)->map(fn ($entity) => $entity->toDomain())->toArray()
        );
    }

}
