<?php

namespace App\Infra\Repository;

use App\Domain\RepositoryContract\LoginHistoryRepositoryContract;
use App\Domain\Entity\LoginHistory;

use App\Infra\Database\EntitiesOrm\{LoginHistory as LoginHistoryORM};
use Doctrine\ORM\EntityManagerInterface;

class LoginHistoryRepository extends BaseRepository implements LoginHistoryRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(LoginHistoryORM::class)
        );
    }

    public function create(LoginHistory $loginHistory): void
    {           
        $loginHistoryOrm = LoginHistoryORM::fromDomain($loginHistory);
        
        if (!empty($loginHistory->getUserId())) $loginHistoryOrm->userId = $loginHistory->getUserId();
        if (!empty($loginHistory->getEmployeeId())) $loginHistoryOrm->employeeId = $loginHistory->getEmployeeId();
        
        $this->persist($loginHistoryOrm)->toDomain();
    }

    public function findMostRecent(int $id, bool $realUser): ?LoginHistory
    {
        // Define o campo a ser filtrado com base no valor de $realUser
        $field = $realUser ? 'lh.userId' : 'lh.employeeId';

        // Cria a query
        $qb = $this->entityManager->createQueryBuilder();

        $loginHistory = $qb
            ->select('lh')
            ->from(LoginHistoryORM::class, 'lh')
            ->where("$field = :id")
            ->setParameter('id', $id)
            ->orderBy('lh.expiresAt', 'DESC') // Ordenar por createdAt em ordem decrescente
            ->setMaxResults(1) // Garantir que somente o mais recente seja retornado
            ->getQuery()
            ->getOneOrNullResult();

        return $loginHistory ? $loginHistory->toDomain() : null;
    }

    public function update(LoginHistory $loginHistory): LoginHistory
    {
        $loginHistoryOrm = parent::getEntityById($loginHistory->getId());
        $loginHistoryOrm->expiresAt = $loginHistory->getExpiresAt();
        
        return $this->persist($loginHistoryOrm)->toDomain();
    }

}
