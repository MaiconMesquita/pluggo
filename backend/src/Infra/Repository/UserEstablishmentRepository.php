<?php

namespace App\Infra\Repository;

use App\Domain\Entity\DTO\UserEstablishmentDTO;
use App\Domain\Entity\{PaginatedEntities,UserEstablishment};
use App\Domain\RepositoryContract\UserEstablishmentRepositoryContract;
use App\Infra\Database\EntitiesOrm\{
    Establishment, 
    User, 
    UserCard, 
    UserEstablishment as UserEstablishmentORM};
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UserEstablishmentRepository extends BaseRepository implements UserEstablishmentRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(UserEstablishmentORM::class)
        );
    }

    public function create(UserEstablishment $userEstablishment): UserEstablishment
    {   
        $userEstablishmentOrm = UserEstablishmentORM::fromDomain($userEstablishment);
        
        if ($userEstablishment->getUserId()) {
            $userEstablishmentOrm->user = $this->entityManager->getReference(
                User::class,
                $userEstablishment->getUserId()
            );
        }

        if ($userEstablishment->getEstablishmentId()) {
            $userEstablishmentOrm->establishment = $this->entityManager->getReference(
                Establishment::class,
                $userEstablishment->getEstablishmentId()
            );
        }

        if ($userEstablishment->getUserCardId()) {
            $userEstablishmentOrm->userCard = $this->entityManager->getReference(
                UserCard::class,
                $userEstablishment->getUserCardId()
            );
        }

        return $this->persist($userEstablishmentOrm)->toDomain();       
    }

    public function findOneBy(array $params): ?UserEstablishment
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

    public function findByEstablishmentIdAndUserId(int $limit, int $offset, int $establishmentId, int $userId): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();
        

        // Seleciona os campos necessários das tabelas users, establishments e user_establishment
        $qb->select('ue.id, ue.userId, ue.establishmentId, u.name AS userName, u.userType, e.tradeName AS establishmentName, ue.createdAt, ue.updatedAt')
            ->from(UserEstablishmentORM::class, 'ue')
            ->join('ue.user', 'u')  // JOIN entre UserEstablishment e User
            ->join('ue.establishment', 'e')  // JOIN entre UserEstablishment e Establishment
            ->where('ue.userId = :userId')
            ->andWhere('ue.establishmentId = :establishmentId')
            ->andWhere('ue.deactivationStatus = false') // filtro adicionado
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Executa a consulta e obtém os resultados
        $results = $qb->getQuery()->getArrayResult();

        // Mapeia os resultados para a DTO
        $userEstablishments = array_map(
            fn($row) => new UserEstablishmentDTO(
                id: (int) $row['id'],
                userId: (int) $row['userId'],
                establishmentId: (int) $row['establishmentId'],
                userName: (string) $row['userName'],
                userType: (string) $row['userType'],
                establishmentName: (string) $row['establishmentName'],
                createdAt: ($row['createdAt'] instanceof \DateTime ? $row['createdAt'] : new \DateTime($row['createdAt']))->format('Y-m-d H:i:s'),
                updatedAt: ($row['updatedAt'] instanceof \DateTime ? $row['updatedAt'] : new \DateTime($row['updatedAt']))->format('Y-m-d H:i:s'),
            ),
            $results
        );

        // Total de itens
        $totalItems = $this->entityManager->createQueryBuilder()
            ->select('count(ue.id)')
            ->from(UserEstablishmentORM::class, 'ue')
            ->where('ue.userId = :userId')
            ->andWhere('ue.establishmentId = :establishmentId')
            ->andWhere('ue.deactivationStatus = false') // filtro na contagem
            ->setParameters([
                'userId' => $userId,
                'establishmentId' => $establishmentId,
            ])
            ->getQuery()
            ->getSingleScalarResult();

        // Retorna os resultados paginados
        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $userEstablishments
        );
    }

    public function findByEstablishmentId(int $limit, int $offset, int $establishmentId): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();
        

        // Seleciona os campos necessários das tabelas users, establishments e user_establishment
        $qb->select('ue.id, ue.userId, ue.establishmentId, u.name AS userName, u.userType, e.tradeName AS establishmentName, ue.createdAt, ue.updatedAt')
            ->from(UserEstablishmentORM::class, 'ue')
            ->join('ue.user', 'u')
            ->join('ue.establishment', 'e')
            ->where('ue.establishmentId = :establishmentId')
            ->andWhere('ue.deactivationStatus = false') // filtro adicionado
            ->setParameter('establishmentId', $establishmentId)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        
        // Executa a consulta e obtém os resultados
        $results = $qb->getQuery()->getArrayResult();

        // Mapeia os resultados para a DTO
        $userEstablishments = array_map(
            fn($row) => new UserEstablishmentDTO(
                id: (int) $row['id'],
                userId: (int) $row['userId'],
                establishmentId: (int) $row['establishmentId'],
                userName: (string) $row['userName'],
                userType: (string) $row['userType'],
                establishmentName: (string) $row['establishmentName'],
                createdAt: ($row['createdAt'] instanceof \DateTime ? $row['createdAt'] : new \DateTime($row['createdAt']))->format('Y-m-d H:i:s'),
                updatedAt: ($row['updatedAt'] instanceof \DateTime ? $row['updatedAt'] : new \DateTime($row['updatedAt']))->format('Y-m-d H:i:s'),
            ),
            $results
        );

        // Total de itens
        $totalItems = $this->entityManager->createQueryBuilder()
            ->select('count(ue.id)')
            ->from(UserEstablishmentORM::class, 'ue')
            ->where('ue.establishmentId = :establishmentId')
            ->andWhere('ue.deactivationStatus = false') // filtro na contagem
            ->setParameter('establishmentId', $establishmentId)
            ->getQuery()
            ->getSingleScalarResult();


        // Retorna os resultados paginados
        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $userEstablishments
        );
    }

    public function findByUserId(int $limit, int $offset, int $userId): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('ue.id, ue.userId, ue.establishmentId, u.name AS userName, u.userType, e.tradeName AS establishmentName, ue.createdAt, ue.updatedAt')
            ->from(UserEstablishmentORM::class, 'ue')
            ->join('ue.user', 'u')
            ->join('ue.establishment', 'e')
            ->where('ue.userId = :userId')
            ->andWhere('ue.deactivationStatus = false')  // filtro adicionado
            ->setParameter('userId', $userId)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $results = $qb->getQuery()->getArrayResult();

        $userEstablishments = array_map(
            fn($row) => new UserEstablishmentDTO(
                id: (int) $row['id'],
                userId: (int) $row['userId'],
                establishmentId: (int) $row['establishmentId'],
                userName: (string) $row['userName'],
                userType: (string) $row['userType'],
                establishmentName: (string) $row['establishmentName'],
                createdAt: ($row['createdAt'] instanceof \DateTime ? $row['createdAt'] : new \DateTime($row['createdAt']))->format('Y-m-d H:i:s'),
                updatedAt: ($row['updatedAt'] instanceof \DateTime ? $row['updatedAt'] : new \DateTime($row['updatedAt']))->format('Y-m-d H:i:s'),
            ),
            $results
        );

        // Total de itens (com filtro)
        $totalItems = $this->entityManager->createQueryBuilder()
            ->select('count(ue.id)')
            ->from(UserEstablishmentORM::class, 'ue')
            ->where('ue.userId = :userId')
            ->andWhere('ue.deactivationStatus = false')  // filtro na contagem
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        return new PaginatedEntities(
            totalItems: (int) $totalItems,
            items: $userEstablishments
        );
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('ue.id, ue.userId, ue.establishmentId, u.name AS userName, u.userType, e.tradeName AS establishmentName, ue.createdAt, ue.updatedAt')
            ->from(UserEstablishmentORM::class, 'ue')
            ->join('ue.user', 'u')
            ->join('ue.establishment', 'e')
            ->where('ue.deactivationStatus = false')  // filtro adicionado
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $results = $qb->getQuery()->getArrayResult();

        $userEstablishments = array_map(
            fn($row) => new UserEstablishmentDTO(
                id: (int) $row['id'],
                userId: (int) $row['userId'],
                establishmentId: (int) $row['establishmentId'],
                userName: (string) $row['userName'],
                userType: (string) $row['userType'],
                establishmentName: (string) $row['establishmentName'],
                createdAt: ($row['createdAt'] instanceof \DateTime ? $row['createdAt'] : new \DateTime($row['createdAt']))->format('Y-m-d H:i:s'),
                updatedAt: ($row['updatedAt'] instanceof \DateTime ? $row['updatedAt'] : new \DateTime($row['updatedAt']))->format('Y-m-d H:i:s'),
            ),
            $results
        );

        // Total de itens (com filtro)
        $totalItems = $this->entityManager->createQueryBuilder()
            ->select('count(ue.id)')
            ->from(UserEstablishmentORM::class, 'ue')
            ->where('ue.deactivationStatus = false')  // filtro na contagem
            ->getQuery()
            ->getSingleScalarResult();

        return new PaginatedEntities(
            totalItems: (int) $totalItems,
            items: $userEstablishments
        );
    }

    public function countAll(array $params = []): int
    {
        $params['deactivationStatus'] = false;
        return $this->repository->count($params);
    }

    public function update(UserEstablishment $userEstablishment): UserEstablishment
    {
        $userEstablishmentOrm = parent::getEntityById($userEstablishment->getId());

        $userEstablishmentOrm->status = $userEstablishment->getStatus();
        $userEstablishmentOrm->deactivationStatus = $userEstablishment->getDeactivationStatus();
        $userEstablishmentOrm->deactivationDate = $userEstablishment->getDeactivationDate();
        

        return $this->persist($userEstablishmentOrm)->toDomain();
    }

    public function findByUserIdAndSegmentId(int $userId, int $segmentId): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('ue')
            ->from(UserEstablishmentORM::class, 'ue')
            ->join('ue.establishment', 'e')
            ->join('e.cnaeMcc', 'cm')
            ->where('cm.segmentId = :segmentId')
            ->andWhere('ue.user = :userId')
            ->andWhere('ue.deactivationStatus = false') // filtro adicionado
            ->setParameter('segmentId', $segmentId)
            ->setParameter('userId', $userId);

        $results = $qb->getQuery()->getResult();

        return array_map(fn($ue) => $ue->toDomain(), $results);
    }

}
