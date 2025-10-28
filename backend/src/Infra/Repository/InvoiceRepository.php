<?php

namespace App\Infra\Repository;

use App\Domain\Entity\{Invoice, PaginatedEntities};
use App\Domain\RepositoryContract\InvoiceRepositoryContract;
use App\Infra\Database\EntitiesOrm\{Invoice as InvoiceORM, UserCard};
use Doctrine\ORM\EntityManagerInterface;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(InvoiceORM::class)
        );
    }

    public function create(Invoice $invoice): Invoice
    {   
        $invoiceOrm = InvoiceORM::fromDomain($invoice);
        
        if ($invoice->getUserCardId()) {
            $invoiceOrm->userCard = $this->entityManager->getReference(
                UserCard::class,
                $invoice->getUserCardId()
            );
        }

        return $this->persist($invoiceOrm)->toDomain();       
    }

    public function getById(int $id): Invoice
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        $criteria = $params;

        // Filtrar por múltiplos userCardIds, se fornecido
        if (!empty($params['userCardIds']) && is_array($params['userCardIds'])) {
            $criteria['userCardId'] = $params['userCardIds'];
            unset($criteria['userCardIds']); // Remove o parâmetro customizado
        }

        return new PaginatedEntities(
            totalItems: $this->repository->count($criteria),
            items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
        );
    }

    // Funções auxiliares para conversão de data e data/hora
    public function convertToDate($date)
    {
        if ($date instanceof \DateTime) {
            // Converte para string no formato 'Y-m-d H:i:s'
            return strtotime($date->format('Y-m-d H:i:s'));
        }

        return strtotime($date); // Se já for uma string, use diretamente
    }

    public function convertToDateTime($date)
    {
        if ($date instanceof \DateTime) {
            // Converte para string no formato 'Y-m-d H:i:s'
            return strtotime($date->format('Y-m-d H:i:s'));
        }

        return strtotime($date); // Se já for uma string, use diretamente
    }

    public function countAll(array $params = []): int
    {
        $params['deactivationStatus'] = false;
        return $this->repository->count($params);
    }

    public function findOneBy(array $params): ?Invoice
    {
        $params['deactivationStatus'] = false;
        $invoiceOrm = $this->repository->findOneBy(
            $params
        );

        if ($invoiceOrm === null) {
            return null;
        }

        return $invoiceOrm->toDomain();
    }

    public function findMostRecent(int $userCardId): ?Invoice
    {
        // Use o método createQueryBuilder para criar a consulta com ordenação
        $qb = $this->entityManager->createQueryBuilder();

        $invoice = $qb
            ->select('i')
            ->from(InvoiceORM::class, 'i')
            ->where('i.userCardId = :userCardId')
            ->andWhere('i.deactivationStatus = false')
            ->setParameter('userCardId', $userCardId)
            ->orderBy('i.closingDate', 'DESC') // Ordenar por createdAt em ordem decrescente
            ->setMaxResults(1) // Garantir que somente o mais recente seja retornado
            ->getQuery()
            ->getOneOrNullResult();

        return $invoice ? $invoice->toDomain() : null;
    }

    public function update(Invoice $invoice): Invoice
    {
        $invoiceOrm = parent::getEntityById($invoice->getId());
        $invoiceOrm->outstandingBalance = $invoice->getOutstandingBalance();
        $invoiceOrm->balanceWithFee = $invoice->getBalanceWithFee();
        $invoiceOrm->creditBalance = $invoice->getCreditBalance();
        $invoiceOrm->revolvingCreditBalance = $invoice->getRevolvingCreditBalance();
        $invoiceOrm->consolidatedInvoiceId = $invoice->getConsolidatedInvoiceId();

        $invoiceOrm->billedAmount = $invoice->getBilledAmount();
        $invoiceOrm->paidAmount = $invoice->getPaidAmount();

        $invoiceOrm->deactivationDate = $invoice->getDeactivationDate();
        $invoiceOrm->deactivationStatus = $invoice->getDeactivationStatus();
        $invoiceOrm->status = $invoice->getStatus();
        
        return $this->persist($invoiceOrm)->toDomain();
    }

    public function findRelevantInvoice(int $userCardId): ?Invoice
    {
        $qb = $this->entityManager->createQueryBuilder();

        // Primeiro, tenta buscar a fatura mais antiga com saldo > 0
        $oldestWithBalance = $qb
            ->select('i')
            ->from(InvoiceORM::class, 'i')
            ->where('i.userCardId = :userCardId')
            ->andWhere('i.balanceWithFee > 0')
            ->andWhere('i.deactivationStatus = false')
            ->setParameter('userCardId', $userCardId)
            ->orderBy('i.createdAt', 'ASC') // Mais antiga primeiro
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($oldestWithBalance) {
            return $oldestWithBalance->toDomain();
        }

        // Se não encontrou, retorna a mais recente (sem filtro de saldo)
        $latest = $this->entityManager->createQueryBuilder()
            ->select('i')
            ->from(InvoiceORM::class, 'i')
            ->where('i.userCardId = :userCardId')
            ->andWhere('i.deactivationStatus = false')
            ->setParameter('userCardId', $userCardId)
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $latest ? $latest->toDomain() : null;
    }

    public function hasCriticalInvoices(int $userCardId): bool
    {
        $qb = $this->entityManager->createQueryBuilder();

        // Conta faturas com status 'overdue'
        $overdueCount = (int) $qb
            ->select('COUNT(i.id)')
            ->from(InvoiceORM::class, 'i')
            ->where('i.userCardId = :userCardId')
            ->andWhere('i.status = :status')
            ->andWhere('i.deactivationStatus = false') 
            ->setParameter('userCardId', $userCardId)
            ->setParameter('status', 'overdue')
            ->getQuery()
            ->getSingleScalarResult();

        if ($overdueCount >= 2) {
            return true;
        }

        // Conta faturas com balanceWithFee > 0
        $qb = $this->entityManager->createQueryBuilder();
        $balanceCount = (int) $qb
            ->select('COUNT(i.id)')
            ->from(InvoiceORM::class, 'i')
            ->where('i.userCardId = :userCardId')
            ->andWhere('i.balanceWithFee > 0')
            ->andWhere('i.deactivationStatus = false')
            ->setParameter('userCardId', $userCardId)
            ->getQuery()
            ->getSingleScalarResult();

        if ($balanceCount >= 2) {
            // Busca a fatura mais recente
            $recentInvoice = $this->entityManager->createQueryBuilder()
                ->select('i')
                ->from(InvoiceORM::class, 'i')
                ->where('i.userCardId = :userCardId')
                ->andWhere('i.deactivationStatus = false')
                ->setParameter('userCardId', $userCardId)
                ->orderBy('i.createdAt', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if ($recentInvoice && $recentInvoice->dueDate instanceof \DateTime) {
                $now = new \DateTime();
                if ($now > $recentInvoice->dueDate) {
                    return true;
                }
            }
        }

        return false;
    }

    public function findMostRecentInvoicesByUserCardId(int $userCardId, int $limit = 3): array
    {
        $ormInvoices = $this->entityManager
            ->getRepository(InvoiceORM::class)
            ->createQueryBuilder('i')
            ->where('i.userCardId = :userCardId')
            ->andWhere('i.deactivationStatus = false') 
            ->setParameter('userCardId', $userCardId)
            ->orderBy('i.closingDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        return array_map(fn(InvoiceORM $orm) => $orm->toDomain(), $ormInvoices);
    }
}
