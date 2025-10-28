<?php

namespace App\Infra\Repository;

use App\Domain\Entity\BrandedCardRequest;
use App\Domain\RepositoryContract\BrandedCardRequestRepositoryContract;
use App\Infra\Database\EntitiesOrm\BrandedCardRequest as BrandedCardRequestORM;
use Doctrine\ORM\EntityManagerInterface;

class BrandedCardRequestRepository extends BaseRepository implements BrandedCardRequestRepositoryContract
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(BrandedCardRequestORM::class)
        );
    }

    public function create(BrandedCardRequest $domain): BrandedCardRequest
    {
        $orm = BrandedCardRequestORM::fromDomain($domain);
        $this->persist($orm);
        return $orm->toDomain();
    }

    public function update(BrandedCardRequest $domain): BrandedCardRequest
    {
        /** @var BrandedCardRequestORM $orm */
        $orm = parent::getEntityById($domain->getId());

        // Atualiza campos
        $orm->cardType = $domain->getCardType();
        $orm->productType = $domain->getProductType();
        $orm->creditLimit = $domain->getCreditLimit();
        $orm->embossingName = $domain->getEmbossingName();
        $orm->invoiceDueDateCode = $domain->getInvoiceDueDateCode();
        $orm->invoiceDeliveryType = $domain->getInvoiceDeliveryType();

        $this->persist($orm);
        return $orm->toDomain();
    }

    public function getById(int $id): ?BrandedCardRequest
    {
        $orm = parent::getEntityById($id);
        if (!$orm) {
            return null;
        }
        return $orm->toDomain();
    }

    public function getByApprovalRequestId(int $approvalRequestId): ?BrandedCardRequest
    {
        $orm = $this->repository->findOneBy([
            'approvalRequest' => $approvalRequestId
        ]);
        return $orm?->toDomain();
    }
}
