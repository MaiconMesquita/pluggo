<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Domain\Entity\Employee;
use App\Domain\Entity\PaginatedEntities;
use App\Infra\Database\EntitiesOrm\{Employee as EmployeeORM};
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(EmployeeORM::class)
        );
    }

    public function create(Employee $employee): Employee
    {   
        $employeeOrm = EmployeeORM::fromDomain($employee);

        return $this->persist($employeeOrm)->toDomain();
    }

    public function getById(int $id): Employee
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

    public function update(Employee $employee): Employee
    {   
        
        $employeeOrm = parent::getEntityById($employee->getId());
 
        if (!empty($employee->getName())) $employeeOrm->name = $employee->getName();
        if (!empty($employee->getPhone())) $employeeOrm->phone = (string) $employee->getPhone();        
        if (!empty($employee->getEmail())) $employeeOrm->email = $employee->getEmail();   
        if (!empty($employee->getPassword())) $employeeOrm->password = $employee->getPassword();
        if (!empty($employee->getChangePassword())) $employeeOrm->changePassword = $employee->getChangePassword();
        if (!empty($employee->getStatus())) $employeeOrm->status = $employee->getStatus();
        if (!empty($employee->getDeviceid())) $employeeOrm->deviceId = $employee->getDeviceid();  
       if (!empty($employee->getOneSignalId())) $employeeOrm->oneSignalId = $employee->getOneSignalId();
        if (!empty($employee->getCpf())) $employeeOrm->cpf = $employee->getCpf();
         $employeeOrm->deactivationDate = $employee->getDeactivationDate();
        $employeeOrm->passwordAttempt = $employee->getPasswordAttempt();

        return $this->persist($employeeOrm)->toDomain();
    }

    public function findOneBy(array $params): ?Employee
    {
        $employeeOrm = $this->repository->findOneBy(
            $params
        );

        if ($employeeOrm === null) {
            return null;
        }

        return $employeeOrm->toDomain();
    }

    public function searchEmployees(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
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
