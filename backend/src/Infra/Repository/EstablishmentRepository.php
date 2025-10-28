<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Establishment;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\EstablishmentRepositoryContract;
use App\Infra\Database\EntitiesOrm\{CnaeMcc, Establishment as EstablishmentORM};
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class EstablishmentRepository extends BaseRepository implements EstablishmentRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(EstablishmentORM::class)
        );
    }

    public function create(Establishment $establishment): Establishment
    {
        $establishmentOrm = EstablishmentORM::fromDomain($establishment);

        if (!empty($establishment->getCnaeMccId())) {
            $establishmentOrm->cnaeMcc = $this->entityManager->getReference(
                CnaeMcc::class,
                $establishment->getCnaeMccId()
            );
        }

        return $this->persist($establishmentOrm)->toDomain();
    }

    public function getAllPaginated(?int $limit = null, ?int $offset = null, array $params = []): PaginatedEntities
    {
        $params['deactivationStatus'] = false;
        $criteria = $params;
        // Filtrar por múltiplos ids, se fornecido
        if (!empty($params['ids']) && is_array($params['ids'])) {
            $criteria['id'] = $params['ids'];
            unset($criteria['ids']); // Remove o parâmetro customizado
        }
        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $criteria),
            items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
        );
    }

    public function getById(int $id): Establishment
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function update(Establishment $establishment): Establishment
    {
        $establishmentOrm = parent::getEntityById($establishment->getId());

        if (!empty($establishment->getCnpj())) $establishmentOrm->cnpj = $establishment->getCnpj();
        if (!empty($establishment->getCpf())) $establishmentOrm->cpf = $establishment->getCpf();
        if (!empty($establishment->getBusinessName())) $establishmentOrm->businessName = $establishment->getBusinessName();
        if (!empty($establishment->getTradeName())) $establishmentOrm->tradeName = $establishment->getTradeName();
        if (!empty($establishment->getEmail())) $establishmentOrm->email = $establishment->getEmail();
        if (!empty($establishment->getPhone())) $establishmentOrm->phone = $establishment->getPhone();
        if (!empty($establishment->getStreet())) $establishmentOrm->street = $establishment->getStreet();
        if (!empty($establishment->getNumber())) $establishmentOrm->number = $establishment->getNumber();
        if (!empty($establishment->getComplement())) $establishmentOrm->complement = $establishment->getComplement();
        if (!empty($establishment->getNeighborhood())) $establishmentOrm->neighborhood = $establishment->getNeighborhood();
        if (!empty($establishment->getCity())) $establishmentOrm->city = $establishment->getCity();
        if (!empty($establishment->getState())) $establishmentOrm->state = $establishment->getState();
        if (!empty($establishment->getPostalCode())) $establishmentOrm->postalCode = $establishment->getPostalCode();
        if (!empty($establishment->getSecretKeyForSells())) {
            $establishmentOrm->secretKeyForSells = $establishment->getSecretKeyForSells();
        }
        if (!empty($establishment->getAlias())) {
            $establishmentOrm->alias = $establishment->getAlias();
        }
        if (!empty($establishment->getChangePassword())) $establishmentOrm->changePassword = $establishment->getChangePassword();
        if (!empty($establishment->getDeviceid())) $establishmentOrm->deviceId = $establishment->getDeviceid();
        if (!empty($establishment->getLatitude())) $establishmentOrm->latitude = $establishment->getLatitude();
        if (!empty($establishment->getLongitude())) $establishmentOrm->longitude = $establishment->getLongitude();
        if (!empty($establishment->getOneSignalId())) $establishmentOrm->oneSignalId = $establishment->getOneSignalId();
        if (!empty($establishment->getPassword())) $establishmentOrm->password = $establishment->getPassword();
        $establishmentOrm->acceptedTermsOfUse = $establishment->getAcceptedTermsOfUse();
        $establishmentOrm->acceptedAccreditationTerms = $establishment->getAcceptedAccreditationTerms();
        $establishmentOrm->passwordAttempt = $establishment->getPasswordAttempt();
        $establishmentOrm->totalBalance = $establishment->getTotalBalance();
        $establishmentOrm->amountToReceive = $establishment->getAmountToReceive();
        $establishmentOrm->amountToReceiveWithFee = $establishment->getAmountToReceiveWithFee();
        $establishmentOrm->splitDiscount = $establishment->getSplitDiscount();
        $establishmentOrm->captureFee = $establishment->getCaptureFee();
        $establishmentOrm->availableCreditBalance = $establishment->getAvailableCreditBalance();
        $establishmentOrm->status = $establishment->getStatus();
        $establishmentOrm->customerLimit = $establishment->getCustomerLimit();
        $establishmentOrm->splitPercentage = $establishment->getSplitPercentage();
        if (!empty($establishment->getMaximumNumberInstallments())) $establishmentOrm->maximumNumberInstallments = $establishment->getMaximumNumberInstallments();
        $establishmentOrm->deactivationDate = $establishment->getDeactivationDate();
        $establishmentOrm->deactivationStatus = $establishment->getDeactivationStatus();

        return $this->persist($establishmentOrm)->toDomain();
    }

    public function findBy(array $params): array
    {
        return $this->entityManager
            ->getRepository(Establishment::class)
            ->findBy($params);
    }


    public function findOneBy(array $params): ?Establishment
    {
        $params['deactivationStatus'] = false;
        $establishmentOrm = $this->repository->findOneBy(
            $params
        );

        if ($establishmentOrm === null) {
            return null;
        }

        return $establishmentOrm->toDomain();
    }

    public function getByAlias(string $alias): ?Establishment
    {
        $establishmentOrm = $this->repository->findOneBy([
            'alias' => $alias,
        ]);

        return $establishmentOrm?->toDomain();
    }


    public function searchEstablishments(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = Criteria::create();

        $criteria->andWhere(Criteria::expr()->eq('deactivationStatus', false));

        // Filtrar por múltiplos ids, se fornecido
        if (!empty($params['ids']) && is_array($params['ids'])) {
            $criteria->andWhere(Criteria::expr()->in('id', $params['ids']));
        }

        // Aplicar filtro LIKE se 'field' e 'filter' forem fornecidos
        if (!empty($params['field']) && !empty($params['filter'])) {
            $criteria->andWhere(
                Criteria::expr()->contains($params['field'], $params['filter'])
            );
        }

        // Filtrar por employeeType se fornecido
        if (!empty($params['status'])) {
            $criteria->andWhere(Criteria::expr()->eq('status', $params['status'] == 1 ? true : false));
        }

        // Filtrar por superiorId se fornecido
        if (!empty($params['supplierStatus'])) {
            $criteria->andWhere(Criteria::expr()->eq('supplierStatus', $params['supplierStatus'] == 1 ? true : false));
        }

        // Contar total de itens
        $totalItems = $this->repository->matching($criteria)->count();

        // Aplicar limite e offset
        if ($limit !== null) {
            $criteria->setMaxResults($limit);
        }
        if ($offset !== null) {
            $criteria->setFirstResult($offset);
        }

        return new PaginatedEntities(
            totalItems: $totalItems,
            items: $this->repository->matching($criteria)->map(fn($entity) => $entity->toDomain())->toArray()
        );
    }

    public function getSegmentIdByCnaeMccId(int $cnaeMccId): ?int
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('c.segmentId')
            ->from(EstablishmentORM::class, 'e')
            ->join('e.cnaeMcc', 'c')
            ->where('e.cnaeMcc = :cnaeMccId')
            ->setParameter('cnaeMccId', $cnaeMccId)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result['segmentId'] ?? null;
    }
}
