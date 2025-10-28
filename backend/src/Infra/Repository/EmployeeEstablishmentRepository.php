<?php

namespace App\Infra\Repository;

use App\Domain\Entity\{EmployeeEstablishment, PaginatedEntities};
use App\Domain\RepositoryContract\EmployeeEstablishmentRepositoryContract;
use App\Infra\Database\EntitiesOrm\{Establishment, Employee, EmployeeEstablishment as EmployeeEstablishmentORM};
use Doctrine\ORM\EntityManagerInterface;

class EmployeeEstablishmentRepository extends BaseRepository implements EmployeeEstablishmentRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(EmployeeEstablishmentORM::class)
        );
    }

    public function create(EmployeeEstablishment $userEstablishment): void
    {   
        $userEstablishmentOrm = EmployeeEstablishmentORM::fromDomain($userEstablishment);
        
        if ($userEstablishment->getEmployeeId()) {
            $userEstablishmentOrm->employee = $this->entityManager->getReference(
                Employee::class,
                $userEstablishment->getEmployeeId()
            );
        }

        if ($userEstablishment->getEstablishmentId()) {
            $userEstablishmentOrm->establishment = $this->entityManager->getReference(
                Establishment::class,
                $userEstablishment->getEstablishmentId()
            );
        }

        $this->persist($userEstablishmentOrm)->toDomain();       
    }

    public function getById(int $id): EmployeeEstablishment
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;

        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }

    public function countAll(array $params = []): int
    {
         $params['deactivationStatus'] = false;

        return $this->repository->count($params);
    }

    public function findOneBy(array $params): ?EmployeeEstablishment
    {
        $params['deactivationStatus'] = false;

        $employeeEstablishmentOrm = $this->repository->findOneBy(
            $params
        );

        if ($employeeEstablishmentOrm === null) {
            return null;
        }

        return $employeeEstablishmentOrm->toDomain();
    }

    public function update(EmployeeEstablishment $employeeEstablishment): EmployeeEstablishment
    {
        
        $employeeEstablishmentOrm = parent::getEntityById($employeeEstablishment->getId());

        $employeeEstablishmentOrm->isSupplierEmployee = $employeeEstablishment->getIsSupplierEmployee();
        $employeeEstablishmentOrm->establishmentOwnerStatus = $employeeEstablishment->getEstablishmentOwnerStatus();
        $employeeEstablishmentOrm->initialLimit = $employeeEstablishment->getInitialLimit();
        $employeeEstablishmentOrm->maximumLimit = $employeeEstablishment->getMaximumLimit();
        $employeeEstablishmentOrm->splitValue = $employeeEstablishment->getSplitValue();
        $employeeEstablishmentOrm->deactivationDate = $employeeEstablishment->getDeactivationDate();
        $employeeEstablishmentOrm->deactivationStatus = $employeeEstablishment->getDeactivationStatus();

        return $this->persist($employeeEstablishmentOrm)->toDomain();
    }

    public function findEmployeesByEstablishmentOwnerStatus(int $employeeId, ?bool $listEstablishments = false): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        // Buscar os establishmentId associados ao employeeId informado
        $qb->select('ee.establishmentId')
            ->from(EmployeeEstablishmentORM::class, 'ee')
            ->where('ee.employee = :employeeId')
            ->andWhere('ee.deactivationStatus = false') // <-- aqui
            ->setParameter('employeeId', $employeeId);

        $establishments = $qb->getQuery()->getResult();

        if (empty($establishments)) {
            return [];
        }

        $establishmentIds = array_column($establishments, 'establishmentId');        

        // Buscar os employeeId onde establishmentOwnerStatus = true para os establishments encontrados
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ee.employeeId, ee.establishmentId')
            ->from(EmployeeEstablishmentORM::class, 'ee')
            ->where('ee.establishmentId IN (:establishmentIds)')
            ->andWhere('ee.establishmentOwnerStatus = true')
            ->andWhere('ee.deactivationStatus = false') // <-- aqui
            ->setParameter('establishmentIds', $establishmentIds);

        $employees = $qb->getQuery()->getResult();

        if($listEstablishments)return $establishmentIds;        
        else return array_column($employees, 'employeeId');
        
    }

}
