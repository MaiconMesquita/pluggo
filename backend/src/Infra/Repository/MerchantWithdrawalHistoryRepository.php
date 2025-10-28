<?php

namespace App\Infra\Repository;

use App\Domain\Entity\MerchantWithdrawalHistory;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\MerchantWithdrawalHistoryRepositoryContract;
use App\Infra\Database\EntitiesOrm\{Employee, Establishment, MerchantWithdrawalHistory as MerchantWithdrawalHistoryORM};
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class MerchantWithdrawalHistoryRepository extends BaseRepository implements MerchantWithdrawalHistoryRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(MerchantWithdrawalHistoryORM::class)
        );
    }
    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        return new PaginatedEntities(
            totalItems: $this->repository->count($params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }

    public function create(MerchantWithdrawalHistory $merchantWithdrawalHistory): MerchantWithdrawalHistory
    {           
        $merchantWithdrawalHistoryOrm = MerchantWithdrawalHistoryORM::fromDomain($merchantWithdrawalHistory);
        
        if (!empty($merchantWithdrawalHistory->getEstablishmentId())) {
            $merchantWithdrawalHistoryOrm->establishment = $this->entityManager->getReference(
                Establishment::class,
                $merchantWithdrawalHistory->getEstablishmentId()
            );
        }

        if (!empty($merchantWithdrawalHistory->getEmployeeId())) {
            $merchantWithdrawalHistoryOrm->employee = $this->entityManager->getReference(
                Employee::class,
                $merchantWithdrawalHistory->getEmployeeId()
            );
        }
        
        return $this->persist($merchantWithdrawalHistoryOrm)->toDomain();
    }

    public function findByDateAndFilters(DateTime $date, int $employeeId, int $establishmentId, string $type, ?bool $status = true): ?MerchantWithdrawalHistory
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $merchantWithdrawalHistory = $queryBuilder
            ->select('mwh')
            ->from(MerchantWithdrawalHistoryORM::class, 'mwh')
            ->where('mwh.createdAt BETWEEN :start AND :end')
            ->andWhere('mwh.employeeId = :employeeId')
            ->andWhere('mwh.establishmentId = :establishmentId')
            ->andWhere('mwh.anticipationType = :anticipationType') // Adicionado
            ->andWhere('mwh.paidStatus = :paidStatus') // Adicionado
            ->andWhere('mwh.deactivationStatus = false') 
            ->setParameters([
                'start' => $date->format('Y-m-d 00:00:00'),
                'end' => $date->format('Y-m-d 23:59:59'),
                'employeeId' => $employeeId,
                'establishmentId' => $establishmentId,
                'anticipationType' => $type,
                'paidStatus' => $status
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $merchantWithdrawalHistory ? $merchantWithdrawalHistory->toDomain() : null;
    }

    public function update(MerchantWithdrawalHistory $merchantWithdrawalHistory): MerchantWithdrawalHistory
    {   
        
        $merchantWithdrawalHistoryOrm = parent::getEntityById($merchantWithdrawalHistory->getId());
    
        $merchantWithdrawalHistoryOrm->amountToReceive = $merchantWithdrawalHistory->getAmountToReceive();   
        $merchantWithdrawalHistoryOrm->amountToReceiveWithFee = $merchantWithdrawalHistory->getAmountToReceiveWithFee();   
        $merchantWithdrawalHistoryOrm->discountedByAnticipationFee = $merchantWithdrawalHistory->getDiscountedByAnticipationFee();   
        $merchantWithdrawalHistoryOrm->captureFee = $merchantWithdrawalHistory->getCaptureFee();  
        $merchantWithdrawalHistoryOrm->splitDiscount = $merchantWithdrawalHistory->getSplitDiscount();   
        $merchantWithdrawalHistoryOrm->paidStatus = $merchantWithdrawalHistory->getPaidStatus();   
        $merchantWithdrawalHistoryOrm->deactivationDate = $merchantWithdrawalHistory->getDeactivationDate();
        $merchantWithdrawalHistoryOrm->deactivationStatus = $merchantWithdrawalHistory->getDeactivationStatus();

        return $this->persist($merchantWithdrawalHistoryOrm)->toDomain();
    }

    public function getById(int $id): MerchantWithdrawalHistory
    {
        return parent::getEntityById($id)->toDomain();
    }
}
