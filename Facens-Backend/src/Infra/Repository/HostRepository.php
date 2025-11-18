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

    public function searchHost(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();


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
