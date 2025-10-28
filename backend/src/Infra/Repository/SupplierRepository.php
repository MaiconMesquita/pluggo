<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Supplier;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\SupplierRepositoryContract;
use App\Infra\Database\EntitiesOrm\Supplier as SupplierORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class SupplierRepository extends BaseRepository implements SupplierRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(SupplierORM::class)
        );
    }

    public function create(Supplier $supplier): Supplier
    {   
        $supplierOrm = SupplierORM::fromDomain($supplier);
        return $this->persist($supplierOrm)->toDomain();
    }

    public function getAllPaginated(?int $limit = null, ?int $offset = null, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        $criteria = $params;

        if (!empty($params['ids']) && is_array($params['ids'])) {
            $criteria['id'] = $params['ids'];
            unset($criteria['ids']);
        }

        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $criteria),
            items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
        );
    }

    public function getById(int $id): Supplier
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function update(Supplier $supplier): Supplier
    {
        $supplierOrm = parent::getEntityById($supplier->getId());

        if (!empty($supplier->getCnpj())) $supplierOrm->cnpj = $supplier->getCnpj();
        if (!empty($supplier->getCpf())) $supplierOrm->cpf = $supplier->getCpf();
        if (!empty($supplier->getBusinessName())) $supplierOrm->businessName = $supplier->getBusinessName();
        if (!empty($supplier->getTradeName())) $supplierOrm->tradeName = $supplier->getTradeName();
        if (!empty($supplier->getEmail())) $supplierOrm->email = $supplier->getEmail();
        if (!empty($supplier->getPhone())) $supplierOrm->phone = $supplier->getPhone();
        if (!empty($supplier->getStreet())) $supplierOrm->street = $supplier->getStreet();
        if (!empty($supplier->getNumber())) $supplierOrm->number = $supplier->getNumber();
        if (!empty($supplier->getComplement())) $supplierOrm->complement = $supplier->getComplement();
        if (!empty($supplier->getNeighborhood())) $supplierOrm->neighborhood = $supplier->getNeighborhood();
        if (!empty($supplier->getCity())) $supplierOrm->city = $supplier->getCity();
        if (!empty($supplier->getState())) $supplierOrm->state = $supplier->getState();
        if (!empty($supplier->getPostalCode())) $supplierOrm->postalCode = $supplier->getPostalCode();
        if (!empty($supplier->getAlias())) $supplierOrm->alias = $supplier->getAlias();
        if (!empty($supplier->getPassword())) $supplierOrm->password = $supplier->getPassword();

        $supplierOrm->passwordAttempt = $supplier->getPasswordAttempt();
        if (!empty($supplier->getPassword())) $supplierOrm->password = $supplier->getPassword();

        $supplierOrm->totalBalance = $supplier->getTotalBalance();
        $supplierOrm->amountToReceive = $supplier->getAmountToReceive();
        $supplierOrm->status = $supplier->getStatus();
        $supplierOrm->deactivationStatus = $supplier->getDeactivationStatus();
        $supplierOrm->deactivationDate = $supplier->getDeactivationDate();

        return $this->persist($supplierOrm)->toDomain();
    }

    public function findOneBy(array $params): ?Supplier    
    {
        $params['deactivationStatus'] = false;
        $supplierOrm = $this->repository->findOneBy($params);

        if ($supplierOrm === null) {
            return null;
        }

        return $supplierOrm->toDomain();
    }

    public function getByAlias(string $alias): ?Supplier
    {
        $supplierOrm = $this->repository->findOneBy(['alias' => $alias]);
        return $supplierOrm?->toDomain();
    }

    public function searchSuppliers(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('deactivationStatus', false));

        if (!empty($params['ids']) && is_array($params['ids'])) {
            $criteria->andWhere(Criteria::expr()->in('id', $params['ids']));
        }

        if (!empty($params['field']) && !empty($params['filter'])) {
            $criteria->andWhere(Criteria::expr()->contains($params['field'], $params['filter']));
        }

        if (!empty($params['status'])) {
            $criteria->andWhere(Criteria::expr()->eq('status', $params['status'] == 1 ? true : false));
        }

        if (!empty($params['supplierStatus'])) {
            $criteria->andWhere(Criteria::expr()->eq('supplierStatus', $params['supplierStatus'] == 1 ? true : false));
        }

        $totalItems = $this->repository->matching($criteria)->count();

        if ($limit !== null) $criteria->setMaxResults($limit);
        if ($offset !== null) $criteria->setFirstResult($offset);

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $this->repository->matching($criteria)->map(fn($entity) => $entity->toDomain())->toArray()
        );
    }
}
