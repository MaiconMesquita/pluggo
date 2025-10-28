<?php

namespace App\Infra\Repository;

use App\Domain\Entity\{Payment, PaginatedEntities};
use App\Domain\RepositoryContract\PaymentRepositoryContract;
use App\Infra\Database\EntitiesOrm\{User, Invoice, Payment as PaymentORM, UserCard};
use Doctrine\ORM\EntityManagerInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(PaymentORM::class)
        );
    }

    public function create(Payment $payment): Payment
    {
        $paymentOrm = PaymentORM::fromDomain($payment);

        if ($payment->getInvoiceId()) {
            $paymentOrm->invoice = $this->entityManager->getReference(
                Invoice::class,
                $payment->getInvoiceId()
            );
        }

        if ($payment->getUserId()) {
            $paymentOrm->user = $this->entityManager->getReference(
                User::class,
                $payment->getUserId()
            );
        }

        if ($payment->getUserCardId()) {
            $paymentOrm->userCard = $this->entityManager->getReference(
                UserCard::class,
                $payment->getUserCardId()
            );
        }

        return $this->persist($paymentOrm)->toDomain();
    }

    public function getById(int $id): Payment
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        return new PaginatedEntities(
            totalItems: $this->repository->count($params),
            items: $this->getAll(params: $params,limit: $limit, offset: $offset)
        );
    }

    public function findOneBy(array $params): ?Payment
    {
        $params['deactivationStatus'] = false;
        $userEstablishmentOrm = $this->repository->findOneBy(
            $params
        );

        if ($userEstablishmentOrm === null) {
            return null;
        }

        return $userEstablishmentOrm->toDomain();
    }

    public function update(Payment $payment): Payment
    {
        $paymentOrm = parent::getEntityById($payment->getId());

        $paymentOrm->amountPaid = $payment->getAmountPaid();
        $paymentOrm->description = $payment->getDescription();
        $paymentOrm->invoiceId = $payment->getInvoiceId();
        $paymentOrm->paymentDate = $payment->getPaymentDate();
        $paymentOrm->code = $payment->getCode();
        $paymentOrm->status = $payment->getStatus();
        $paymentOrm->expirationDate = $payment->getExpirationDate();   
        $paymentOrm->deactivationDate = $payment->getDeactivationDate();
        $paymentOrm->deactivationStatus = $payment->getDeactivationStatus();   

        return $this->persist($paymentOrm)->toDomain();
    }

    public function getAllPayments(
        ?int $limit = null,
        ?int $offset = null,
        array $params = [],
        ?array $orderBy = null,
    ): array {
        $params['deactivationStatus'] = false;
        $criteria = $params;
    
        return array_map(
            function (PaymentORM $payment) {
                return $payment->toDomain();
            },
            $this->repository->findBy($criteria, $orderBy, $limit, $offset)
        );
    }
}
