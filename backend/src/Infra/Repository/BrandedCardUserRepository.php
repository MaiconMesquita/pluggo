<?php

namespace App\Infra\Repository;

use App\Domain\Entity\BrandedCardUser;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\Exception\NotFoundException;
use App\Domain\RepositoryContract\BrandedCardUserRepositoryContract;
use App\Infra\Database\EntitiesOrm\BrandedCardUser as BrandedCardUserORM;
use App\Infra\Database\EntitiesOrm\User;
use App\Infra\Database\EntitiesOrm\BrandedCard as BrandedCardORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class BrandedCardUserRepository extends BaseRepository implements BrandedCardUserRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(BrandedCardUserORM::class)
        );
    }

    public function create(BrandedCardUser $brandedCardUser): BrandedCardUser
    {
        $brandedCardUserOrm = BrandedCardUserORM::fromDomain($brandedCardUser);

        if ($brandedCardUser->getUser() && $brandedCardUser->getUser()->getId()) {
            $brandedCardUserOrm->user = $this->entityManager->getReference(
                User::class,
                $brandedCardUser->getUser()->getId()
            );
        }

        foreach ($brandedCardUserOrm->cards as $cardOrm) {
            $cardOrm->brandedCardUser = $brandedCardUserOrm;
        }

        return $this->persist($brandedCardUserOrm)->toDomain();
    }

    public function update(BrandedCardUser $brandedCardUser): BrandedCardUser
    {
        /** @var BrandedCardUserORM $brandedCardUserOrm */
        $brandedCardUserOrm = parent::getEntityById($brandedCardUser->getId());

        // Update fields
        if ($brandedCardUser->getIssuerId())
            $brandedCardUserOrm->issuerId = $brandedCardUser->getIssuerId();
        if ($brandedCardUser->getIsActive() !== null)
            $brandedCardUserOrm->isActive = $brandedCardUser->getIsActive();
        if ($brandedCardUser->getCreditLimit())
            $brandedCardUserOrm->creditLimit = $brandedCardUser->getCreditLimit();
        if ($brandedCardUser->getProductTypeObject())
            $brandedCardUserOrm->productType = $brandedCardUser->getProductType();
        if ($brandedCardUser->getInvoiceInfo()) {
            $brandedCardUserOrm->invoiceDueDateCode = $brandedCardUser->getInvoiceInfo()->getInvoiceDueDateCode();
            $brandedCardUserOrm->invoiceDeliveryType = $brandedCardUser->getInvoiceInfo()->getInvoiceDeliveryType();
            $brandedCardUserOrm->partnerCnpj = $brandedCardUser->getInvoiceInfo()->getPartnerCnpj();
        }
        if ($brandedCardUser->getUpdatedAt())
            $brandedCardUserOrm->updatedAt = $brandedCardUser->getUpdatedAt();

        return $this->persist($brandedCardUserOrm)->toDomain();
    }

    public function getById(string $id, bool $loadRelationships = false): BrandedCardUser
    {
        /** @var BrandedCardUserORM $brandedCardUserOrm */
        $brandedCardUserOrm = parent::getEntityById($id);
        if (!$brandedCardUserOrm) {
            throw new NotFoundException("BrandedCardUser not found with ID: $id");
        }

        if ($loadRelationships) {
            $this->loadRelationships($brandedCardUserOrm);
        }

        return $brandedCardUserOrm->toDomain();
    }

    public function getByIssuerId(string $issuerId, bool $loadRelationships = false): BrandedCardUser
    {
        /** @var BrandedCardUserORM $brandedCardUserOrm */
        $brandedCardUserOrm = $this->repository->findOneBy(['issuerId' => $issuerId]);

        if (!$brandedCardUserOrm) {
            throw new NotFoundException("BrandedCardUser not found with Issuer ID: $issuerId");
        }

        if ($loadRelationships) {
            $this->loadRelationships($brandedCardUserOrm);
        }

        return $brandedCardUserOrm->toDomain();
    }

    private function loadRelationships(BrandedCardUserORM $entity): void
    {
        // Load cards collection
        if ($entity->cards instanceof PersistentCollection) {
            $entity->cards->initialize();
        }

        // Load user relationship if exists
        if (isset($entity->user)) {
            // Force load by accessing a property
            $entity->user->id;
        }
    }

    public function getByUserIdAndProductType(int $userId, string $productType, bool $loadRelationships = false): ?BrandedCardUser
    {
        /** @var BrandedCardUserORM $brandedCardUserOrm */
        $brandedCardUserOrm = $this->repository->findOneBy([
            'user' => $userId,
            'productType' => $productType
        ]);
        /*
        if (!$brandedCardUserOrm) {
            throw new NotFoundException("BrandedCardUser not found with User ID: $userId and Product Type: $productType");
        }
            */

        if ($loadRelationships) {
            $this->loadRelationships($brandedCardUserOrm);
        }

        return $brandedCardUserOrm?->toDomain(); // retorna null se não existir

    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('bcu', 'u')
            ->from(BrandedCardUserORM::class, 'bcu')
            ->leftJoin('bcu.user', 'u')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Filtros dinâmicos
        if (isset($params['productType'])) {
            $qb->andWhere('bcu.productType = :productType')
                ->setParameter('productType', $params['productType']);
        }

        if (isset($params['isActive'])) {
            $qb->andWhere('bcu.isActive = :isActive')
                ->setParameter('isActive', $params['isActive']);
        }

        // Search term (busca por nome do usuário ou issuerId)
        if (isset($params['searchTerm']) && !empty($params['searchTerm'])) {
            $qb->andWhere('(u.name LIKE :searchTerm OR bcu.issuerId LIKE :searchTerm)')
                ->setParameter('searchTerm', '%' . $params['searchTerm'] . '%');
        }

        // Ordenação dinâmica
        if (isset($params['orderBy']) && is_array($params['orderBy'])) {
            foreach ($params['orderBy'] as $field => $direction) {
                $qb->addOrderBy("bcu.$field", $direction);
            }
        } else {
            // Ordenação padrão
            $qb->orderBy('bcu.createdAt', 'DESC');
        }

        $results = $qb->getQuery()->getResult();

        // Total de itens para paginação
        $countQb = $this->entityManager->createQueryBuilder();
        $countQb->select('COUNT(bcu.id)')
            ->from(BrandedCardUserORM::class, 'bcu')
            ->leftJoin('bcu.user', 'u');

        if (isset($params['productType'])) {
            $countQb->andWhere('bcu.productType = :productType')
                ->setParameter('productType', $params['productType']);
        }

        if (isset($params['isActive'])) {
            $countQb->andWhere('bcu.isActive = :isActive')
                ->setParameter('isActive', $params['isActive']);
        }

        if (isset($params['searchTerm']) && !empty($params['searchTerm'])) {
            $countQb->andWhere('(u.name LIKE :searchTerm OR bcu.issuerId LIKE :searchTerm)')
                ->setParameter('searchTerm', '%' . $params['searchTerm'] . '%');
        }

        $totalItems = (int) $countQb->getQuery()->getSingleScalarResult();

        $brandedCardUsers = array_map(function (BrandedCardUserORM $orm) {
            return $orm->toDomain();
        }, $results);

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $brandedCardUsers
        );
    }

    public function getAll(int $limit, int $offset, array $params = [], ?array $orderBy = null): array
    {
        $brandedCardUserOrms = $this->repository->findBy($params, $orderBy, $limit, $offset);

        // Sempre garante que o User está carregado
        foreach ($brandedCardUserOrms as $orm) {
            if (isset($orm->user)) {
                $orm->user->getId(); // Force load
            }
        }

        return array_map(fn($orm) => $orm->toDomain(), $brandedCardUserOrms);
    }
}
