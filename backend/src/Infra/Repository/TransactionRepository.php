<?php

namespace App\Infra\Repository;

use App\Domain\Entity\PaginatedEntities;
use App\Domain\Entity\Transaction;
use App\Domain\RepositoryContract\TransactionRepositoryContract;
use App\Infra\Database\EntitiesOrm\{CnaeMcc, Establishment, Invoice, MerchantWithdrawalHistory, Transaction as TransactionORM, UserCard};
use DateTime;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\EntityManagerInterface;

class TransactionRepository extends BaseRepository implements TransactionRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(TransactionORM::class)
        );
    }

    public function create(Transaction $transaction): Transaction
    {
        $transactionOrm = TransactionORM::fromDomain($transaction);

        $transactionOrm->transactionType = $transaction->getTransactionType()->getType();
        $transactionOrm->status = $transaction->getStatus()->getType();

        if ($transaction->getEstablishmentId()) {
            $transactionOrm->establishment = $this->entityManager->getReference(
                Establishment::class,
                $transaction->getEstablishmentId()
            );
        }

        if ($transaction->getCnaeMccId()) {
            $transactionOrm->cnaeMcc = $this->entityManager->getReference(
                CnaeMcc::class,
                $transaction->getCnaeMccId()
            );
        }

        if ($transaction->getUserCardId()) {
            $transactionOrm->userCard = $this->entityManager->getReference(
                UserCard::class,
                $transaction->getUserCardId()
            );
        }

        if ($transaction->getInvoiceId()) {
            $transactionOrm->invoice = $this->entityManager->getReference(
                Invoice::class,
                $transaction->getInvoiceId()
            );
        }

        return $this->persist($transactionOrm)->toDomain();
    }

    public function getById(int $id): Transaction
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        $criteria = $params;
        // Filtrar por múltiplos status, se fornecido
        if (!empty($params['userCardIds']) && is_array($params['userCardIds'])) {
            $criteria['userCardId'] = $params['userCardIds'];
            unset($criteria['userCardIds']); // Remove o parâmetro customizado
        }
        if (!empty($params['establishmentIds']) && is_array($params['establishmentIds'])) {
            $criteria['establishmentId'] = $params['establishmentIds'];
            unset($criteria['establishmentIds']); // Remove o parâmetro customizado
        }

        return new PaginatedEntities(
            totalItems: $this->repository->count($criteria),
            items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
        );
    }

    public function findOneBy(array $params): ?Transaction
    {
        $params['deactivationStatus'] = false;
        $transactionOrm = $this->repository->findOneBy(
            $params
        );

        if ($transactionOrm === null) {
            return null;
        }

        return $transactionOrm->toDomain();
    }

    public function getAllTransactions(
        ?int $limit = null,
        ?int $offset = null,
        array $params = [],
        ?array $orderBy = null,
    ): array {
        $params['deactivationStatus'] = false;
        $criteria = $params;

        // Filtrar por múltiplos status, se fornecido
        if (!empty($params['statuses']) && is_array($params['statuses'])) {
            $criteria['status'] = $params['statuses'];
            unset($criteria['statuses']); // Remove o parâmetro customizado
        }

        if (!empty($params['userCardIds']) && is_array($params['userCardIds'])) {
            $criteria['userCardId'] = $params['userCardIds'];
            unset($criteria['userCardIds']); // Remove o parâmetro customizado
        }

        return array_map(
            function (TransactionORM $transaction) {
                return $transaction->toDomain();
            },
            $this->repository->findBy($criteria, $orderBy, $limit, $offset)
        );
    }

    public function update(Transaction $transaction): Transaction
    {
        $transactionOrm = parent::getEntityById($transaction->getId());

        $transactionOrm->transactionType = $transaction->getTransactionType()->getType();
        $transactionOrm->availableWithdrawalAmount = $transaction->getAvailableWithdrawalAmount();
        $transactionOrm->anticipationStatus = $transaction->getAnticipationStatus();
        $transactionOrm->anticipationDate = $transaction->getAnticipationDate();
        $transactionOrm->advanceFee = $transaction->getAdvanceFee();
        $transactionOrm->splitAmount = $transaction->getSplitAmount();
        // $transactionOrm->description = $transaction->getDescription();
        // $transactionOrm->amount = $transaction->getAmount();
        // $transactionOrm->installmentCount = $transaction->getInstallmentCount();
        $transactionOrm->status = $transaction->getStatus()->getType();
        $transactionOrm->cancellationDate = $transaction->getCancellationDate();
        $transactionOrm->deactivationStatus = $transaction->getDeactivationStatus();
        $transactionOrm->deactivationDate = $transaction->getDeactivationDate();

        return $this->persist($transactionOrm)->toDomain();
    }

    public function updateRelationship(Transaction $transaction): Transaction
    {
        $transactionOrm = parent::getEntityById($transaction->getId());
        if ($transaction->getInvoiceId()) {
            $transactionOrm->invoice = $this->entityManager->getReference(
                Invoice::class,
                $transaction->getInvoiceId()
            );
        }

        return $this->persist($transactionOrm)->toDomain();
    }

    public function getTransactionsByEstablishmentIds(array $establishmentIds, ?array $filters = []): array
    {
        if (empty($establishmentIds)) {
            return [];
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t')
            ->from(TransactionORM::class, 't')
            ->where('t.deactivationStatus = false')
            ->andWhere('t.establishmentId IN (:establishmentIds)')
            ->setParameter('establishmentIds', $establishmentIds);

        // Aplicando filtros adicionais se houver
        if (!empty($filters['userCardId'])) {
            $qb->andWhere('t.userCardId = :userCardId')
                ->setParameter('userCardId', $filters['userCardId']);
        }

        if (!empty($filters['cnaeMccId'])) {
            $qb->andWhere('t.cnaeMccId = :cnaeMccId')
                ->setParameter('cnaeMccId', $filters['cnaeMccId']);
        }

        if (!empty($filters['purchaseHash'])) {
            $qb->andWhere('t.purchaseHash = :purchaseHash')
                ->setParameter('purchaseHash', $filters['purchaseHash']);
        }

        return $qb->getQuery()->getResult();
    }


    public function getGroupedTransactions(?array $filters = []): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t')
            ->from(TransactionORM::class, 't')
            ->where('t.purchaseHash IS NOT NULL')
            ->andWhere('t.deactivationStatus = false');

        // Aplicando filtros dinamicamente
        if (!empty($filters['userCardId'])) {
            $qb->andWhere('t.userCardId = :userCardId')
                ->setParameter('userCardId', $filters['userCardId']);
        }
        if (!empty($filters['userCardIds'])) {
            $qb->andWhere('t.userCardId IN (:userCardIds)')
                ->setParameter('userCardIds', $filters['userCardIds']);
        }
        if (!empty($filters['establishmentId'])) {
            if (is_array($filters['establishmentId'])) {
                $qb->andWhere('t.establishmentId IN (:establishmentIds)')
                    ->setParameter('establishmentIds', $filters['establishmentId'], ArrayParameterType::INTEGER);
            } else {
                $qb->andWhere('t.establishmentId = :establishmentId')
                    ->setParameter('establishmentId', $filters['establishmentId']);
            }
        }

        if (!empty($filters['cnaeMccId'])) {
            $qb->andWhere('t.cnaeMccId = :cnaeMccId')
                ->setParameter('cnaeMccId', $filters['cnaeMccId']);
        }

        $transactions = $qb->getQuery()->getResult();

        $groupedTransactions = [];

        foreach ($transactions as $transaction) {
            $hash = $transaction->purchaseHash;

            if (!isset($groupedTransactions[$hash])) {
                $groupedTransactions[$hash] = [
                    'hash' => $hash,
                    'originalAmount' => 0.0,
                    'installmentCount' => 0,
                    'statuses' => [],
                    'captureFee' => 0.0,
                    'splitAmount' => 0.0,
                    'cardFee' => 0.0,
                    'availableWithdrawalAmount' => 0.0,
                    'billingFeeAmountToPay' => 0.0,
                    'advanceFee' => 0.0,
                ];
            }

            $groupedTransactions[$hash]['originalAmount'] = (float) $transaction->originalAmount;
            $groupedTransactions[$hash]['description'] = $transaction->description;
            $groupedTransactions[$hash]['installmentCount']++;
            $groupedTransactions[$hash]['transactionType'] = $transaction->transactionType;
            $groupedTransactions[$hash]['statuses'][] = $transaction->status;
            $groupedTransactions[$hash]['months'][] = $transaction->month;
            $groupedTransactions[$hash]['years'][] = $transaction->year;
            $groupedTransactions[$hash]['captureFee'] += (float) $transaction->captureFee;
            $groupedTransactions[$hash]['splitAmount'] += (float) ($transaction->splitAmount ?? 0.0);
            $groupedTransactions[$hash]['cardFee'] += (float) $transaction->cardFee;
            $groupedTransactions[$hash]['availableWithdrawalAmount'] += (float) $transaction->availableWithdrawalAmount;
            $groupedTransactions[$hash]['billingFeeAmountToPay'] += (float) $transaction->billingFeeAmountToPay;
            $groupedTransactions[$hash]['advanceFee'] += (float) ($transaction->advanceFee ?? 0.0);
            $groupedTransactions[$hash]['userCardId'] = $transaction->userCardId;
            $groupedTransactions[$hash]['establishmentId'] = $transaction->establishmentId;
            $groupedTransactions[$hash]['cnaeMccId'] = $transaction->cnaeMccId;
            $groupedTransactions[$hash]['createdAt'] = $transaction->createdAt;
        }

        // Formatar os valores no retorno
        foreach ($groupedTransactions as &$group) {
            $group['originalAmount'] = number_format($group['originalAmount'], 2, '.', '');
            $group['captureFee'] = number_format($group['captureFee'], 2, '.', '');
            $group['splitAmount'] = number_format($group['splitAmount'], 2, '.', '');
            $group['cardFee'] = number_format($group['cardFee'], 2, '.', '');
            $group['availableWithdrawalAmount'] = number_format($group['availableWithdrawalAmount'], 2, '.', '');
            $group['billingFeeAmountToPay'] = number_format($group['billingFeeAmountToPay'], 2, '.', '');
            $group['advanceFee'] = number_format($group['advanceFee'], 2, '.', '');
            $group['createdAt'] = $group['createdAt']->format('Y-m-d H:i:s');
        }

        return array_values($groupedTransactions);
    }

    // Exemplo com SQL nativo
    public function updateStatusAndDateByInvoiceId(int $invoiceId, string $status, DateTime $date): void
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->update(TransactionORM::class, 't')
            ->set('t.status', ':status')
            ->set('t.paymentDate', ':paymentDate')
            ->where('t.invoice = :invoiceId')
            ->andWhere('t.status = :pendingStatus')
            ->setParameter('status', $status)
            ->setParameter('paymentDate', $date)
            ->setParameter('invoiceId', $invoiceId)
            ->setParameter('pendingStatus', 'pending');

        $qb->getQuery()->execute();
    }

    public function findEstablishmentIdFromFirstTransaction(int $invoiceId): ?int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('IDENTITY(t.establishment)')
            ->from(TransactionORM::class, 't')
            ->where('t.invoice = :invoiceId')
            ->andWhere('t.status = :status')
            ->andWhere('t.deactivationStatus = false')
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(1)
            ->setParameter('invoiceId', $invoiceId)
            ->setParameter('status', 'pending');

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result !== null ? (int) $result : null;
    }

    public function getBillingFeeSumByCardPerInvoice(int $invoiceId, string $status): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('IDENTITY(t.userCard) as userCardId, SUM(t.originalInstallment) as totalOriginalInstallment, SUM(t.billingFeeAmountToPay) as totalBillingFeeAmountToPay')
            ->from(TransactionORM::class, 't')
            ->where('t.invoice = :invoiceId')
            ->andWhere('t.status = :status')
            ->andWhere('t.deactivationStatus = false')
            ->groupBy('t.userCard')
            ->setParameters([
                'invoiceId' => $invoiceId,
                'status' => $status,
            ]);

        return $qb->getQuery()->getArrayResult();
    }

    public function getSumOfTransactionAmounts(
        ?int $userId = null,
        ?int $cardId = null,
        ?int $invoiceId = null,
        ?int $establishmentId = null
    ): array {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select(
            'COALESCE(SUM(t.availableWithdrawalAmount), 0) as totalAvailableWithdrawalAmount',
            'COALESCE(SUM(t.billingFeeAmountToPay), 0) as totalBillingFeeAmountToPay',
            'COALESCE(SUM(t.splitAmount), 0) as totalSplitAmount',
            'COALESCE(SUM(t.captureFee), 0) as totalCaptureFee',
            'COALESCE(SUM(t.cardFee), 0) as totalCardFee',
            'COALESCE(SUM(t.advanceFee), 0) as totalAdvanceFee',
            'COALESCE(SUM(t.originalInstallment), 0) as totalOriginalInstallment',
            'COUNT(t.id) as totalTransactions'
        )
            ->from(TransactionORM::class, 't');

        // Se for necessário filtrar por cartão/usuário
        if ($userId !== null || $cardId !== null) {
            $qb->join('t.userCard', 'uc');
        }

        if ($establishmentId !== null) {
            $qb->andWhere('t.establishmentId = :establishmentId')
                ->setParameter('establishmentId', $establishmentId);
        }

        if ($userId !== null && $cardId === null && $invoiceId === null) {
            $qb->andWhere('uc.user = :userId')
                ->setParameter('userId', $userId);
        }

        if ($userId !== null && $cardId !== null && $invoiceId === null) {
            $qb->andWhere('uc.user = :userId')
                ->andWhere('uc.card = :cardId')
                ->setParameter('userId', $userId)
                ->setParameter('cardId', $cardId);
        }

        if ($invoiceId !== null && $userId === null && $cardId === null) {
            $qb->andWhere('t.invoice = :invoiceId')
                ->setParameter('invoiceId', $invoiceId);
        }

        // Garantir que o filtro de ativo esteja sempre aplicado
        $qb->andWhere('t.deactivationStatus = false');

        return $qb->getQuery()->getSingleResult();
    }
}
