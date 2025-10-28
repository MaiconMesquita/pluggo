<?php

namespace App\Infra\Repository;

use App\Domain\Entity\DTO\SmsHistoryDTO;
use App\Domain\Entity\{PaginatedEntities, SmsHistory};
use App\Domain\Entity\ValueObject\SmsType;
use App\Domain\RepositoryContract\SmsHistoryRepositoryContract;
use App\Infra\Database\EntitiesOrm\{
    Card,
    SmsHistory as SmsHistoryORM,
    User,
    Establishment,
    Employee,
};
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class SmsHistoryRepository extends BaseRepository implements SmsHistoryRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(SmsHistoryORM::class)
        );
    }

    public function create(SmsHistory $smsHistory): SmsHistory
    {
        try {
            $sms = SmsHistoryORM::fromDomain($smsHistory);

            if ($smsHistory->getUserId()) {
                $sms->user = $this->entityManager->getReference(
                    User::class,
                    $smsHistory->getUserId()
                );
            }

            if ($smsHistory->getEstablishmentId()) {
                $sms->establishment = $this->entityManager->getReference(
                    Establishment::class,
                    $smsHistory->getEstablishmentId()
                );
            }

            if ($smsHistory->getEmployeeId()) {
                $sms->employee = $this->entityManager->getReference(
                    Employee::class,
                    $smsHistory->getEmployeeId()
                );
            }

            if ($smsHistory->getCardId()) {
                $sms->card = $this->entityManager->getReference(
                    Card::class,
                    $smsHistory->getCardId()
                );
            }

            return $this->persist($sms)->toDomain();
        } catch (\Throwable $th) {
            return $smsHistory;
        }
    }

    public function removeByUserIdAndType(SmsType $type, ?int $userId = null, ?int $employeeId = null, ?int $establishmentId = null, ?int $supplierId = null): void
    {
        // Validar que pelo menos um ID foi fornecido
        if ($userId === null && $employeeId === null && $establishmentId === null && $supplierId === null) {
            throw new InvalidArgumentException('provide a userId or employeeId.');
        }

        // Determinar o critério de busca
        $criteria = ['type' => $type->getType()];

        if ($userId !== null) {
            $criteria['user'] = $userId;
        }

        if ($employeeId !== null) {
            $criteria['employee'] = $employeeId;
        }

        if ($establishmentId !== null) {
            $criteria['establishment'] = $establishmentId;
        }

        if ($supplierId !== null) {
            $criteria['supplier'] = $supplierId;
        }

        // Encontrar todos os registros que correspondem ao critério
        $smsHistories = $this->entityManager->getRepository(SmsHistoryORM::class)
            ->findBy($criteria);

        // Remover cada um dos registros encontrados
        foreach ($smsHistories as $smsHistory) {
            $this->entityManager->remove($smsHistory);
        }

        // Aplicar as mudanças
        $this->entityManager->flush();
    }

    public function removeById(int $id): void
    {
        parent::delete($id);
    }

    public function findMostRecentByUserIdAndType(SmsType $type, ?int $userId = null, ?int $employeeId = null, ?int $establishmentId = null, ?int $supplierId = null): ?SmsHistory
    {
        // Validar que pelo menos um ID foi fornecido
        if ($userId === null && $employeeId === null && $establishmentId === null && $supplierId === null) {
            throw new InvalidArgumentException('É necessário fornecer um userId ou employeeId.');
        }

        // Determinar o critério de busca
        $criteria = [
            'type' => $type->getType(),
            'deactivationStatus' => false,
        ];

        if ($userId !== null) {
            $criteria['user'] = $userId;
        }

        if ($employeeId !== null) {
            $criteria['employee'] = $employeeId;
        }

        if ($establishmentId !== null) {
            $criteria['establishment'] = $establishmentId;
        }

        if ($supplierId !== null) {
            $criteria['supplier'] = $supplierId;
        }

        // Encontrar o registro mais recente usando findOneBy com orderBy
        $smsHistory = $this->entityManager->getRepository(SmsHistoryORM::class)
            ->findOneBy(
                $criteria,
                ['createdAt' => 'DESC']
            );

        return $smsHistory ? $smsHistory->toDomain() : null;
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('s.id, s.code, s.type, s.action, s.url, s.partner, s.userId, s.transactionDescription, s.transactionValue, s.establishmentId, s.maximumNumberInstallments, s.status, s.description, s.createdAt, s.expirationDate')
            ->from(SmsHistoryORM::class, 's')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->where('s.deactivationStatus = false'); // <- Filtro adicionado aqui

        if (!empty($params['userId'])) {
            $qb->andWhere('s.userId = :userId')
                ->setParameter('userId', $params['userId']);
        }

        if (!empty($params['employeeId'])) {
            $qb->andWhere('s.employeeId = :employeeId')
                ->setParameter('employeeId', $params['employeeId']);
        }

        if (!empty($params['establishmentId'])) {
            $qb->andWhere('s.establishmentId = :establishmentId')
                ->setParameter('establishmentId', $params['establishmentId']);
        }


        if (!empty($params['types']) && is_array($params['types'])) {
            $qb->andWhere($qb->expr()->in('s.type', ':types'))
                ->setParameter('types', $params['types']);
        }

        $results = $qb->getQuery()->getArrayResult();

        $smsHistories = array_map(
            fn($row) => new SmsHistoryDTO(
                id: $row['id'],
                code: $row['code'],
                type: $row['type'],
                action: $row['action'],
                url: $row['url'],
                partner: $row['partner'],
                status: $row['status'],
                maximumNumberInstallments: $row['maximumNumberInstallments'],
                transactionDescription: $row['transactionDescription'],
                transactionValue: number_format($row['transactionValue'], 2, '.', ''),
                userId: $row['userId'],
                establishmentId: $row['establishmentId'],
                description: $row['description'],
                createdAt: ($row['createdAt'] instanceof \DateTime ? $row['createdAt'] : new \DateTime($row['createdAt']))->format('Y-m-d H:i:s'),
                expirationDate: ($row['expirationDate'] instanceof \DateTime ? $row['expirationDate'] : new \DateTime($row['expirationDate']))->format('Y-m-d H:i:s'),
            ),
            $results
        );

        // Aplica o mesmo filtro na contagem
        $totalItems = $this->entityManager->createQueryBuilder()
            ->select('count(s.id)')
            ->from(SmsHistoryORM::class, 's')
            ->where($qb->getDQLPart('where')) // Reutiliza os filtros
            ->setParameters($qb->getParameters())
            ->getQuery()
            ->getSingleScalarResult();

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $smsHistories
        );
    }

    public function getById(int $id): SmsHistory
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function update(SmsHistory $smsHistory): SmsHistory
    {
        $smsHistoryOrm = parent::getEntityById($smsHistory->getId());

        if (!empty($smsHistory->getCode())) {
            $smsHistoryOrm->code = $smsHistory->getCode();
        }
        if (!empty($smsHistory->getType())) {
            $smsHistoryOrm->type = $smsHistory->getType();
        }
        if (!empty($smsHistory->getAction())) {
            $smsHistoryOrm->action = $smsHistory->getAction();
        }
        if (!empty($smsHistory->getUrl())) {
            $smsHistoryOrm->url = $smsHistory->getUrl();
        }
        if (!empty($smsHistory->getPartner())) {
            $smsHistoryOrm->partner = $smsHistory->getPartner();
        }
        if (!empty($smsHistory->getDescription())) {
            $smsHistoryOrm->description = $smsHistory->getDescription();
        }
        if (!empty($smsHistory->getDiscount())) {
            $smsHistoryOrm->discount = $smsHistory->getDiscount();
        }
        if (!empty($smsHistory->getUserId())) {
            $smsHistoryOrm->userId = $smsHistory->getUserId();
        }
        if (!empty($smsHistory->getEmployeeId())) {
            $smsHistoryOrm->employeeId = $smsHistory->getEmployeeId();
        }
        if (!empty($smsHistory->getEstablishmentId())) {
            $smsHistoryOrm->establishmentId = $smsHistory->getEstablishmentId();
        }
        if (!empty($smsHistory->getSupplierId())) {
            $smsHistoryOrm->supplierId = $smsHistory->getSupplierId();
        }
        if (!empty($smsHistory->getRequestData())) {
            $smsHistoryOrm->requestData = $smsHistory->getRequestData();
        }
        if (!empty($smsHistory->getResponseData())) {
            $smsHistoryOrm->responseData = $smsHistory->getResponseData();
        }
        if (!empty($smsHistory->getAttempt())) {
            $smsHistoryOrm->attempt = $smsHistory->getAttempt();
        }
        $smsHistoryOrm->status = $smsHistory->getStatus();
        $smsHistoryOrm->maximumNumberInstallments = $smsHistory->getMaximumNumberInstallments();
        $smsHistoryOrm->deactivationDate = $smsHistory->getDeactivationDate();
        $smsHistoryOrm->deactivationStatus = $smsHistory->getDeactivationStatus();

        return $this->persist($smsHistoryOrm)->toDomain();
    }
}
