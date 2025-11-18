<?php

namespace App\Infra\Repository;

use App\Domain\Entity\DTO\UserDTO;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\UserRepositoryContract;
use App\Domain\Entity\User;
use App\Domain\Entity\UserDocument;
use App\Infra\Database\EntitiesOrm\{User as UserORM, UserDocument as UserDocumentORM};
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;


class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(UserORM::class)
        );
    }

    public function create(User $user): User
    {
        $userOrm = UserORM::fromDomain($user);

        $userOrm->userType = $user->getUserType()->getType();

        return $this->persist($userOrm)->toDomain();
    }

    public function update(User $user): User
    {

        $userOrm = parent::getEntityById($user->getId());

        if (!empty($user->getName())) $userOrm->name = $user->getName();
        if (!empty($user->getCpf())) $userOrm->cpf = $user->getCpf();
        if (!empty($user->getRg())) $userOrm->rg = $user->getRg();
        if (!empty($user->getPhone())) $userOrm->phone = (string) $user->getPhone();
        if (!empty($user->getEmail())) $userOrm->email = $user->getEmail();
        if (!empty($user->getStreet())) $userOrm->street = $user->getStreet();
        if (!empty($user->getPostalCode())) $userOrm->postalCode = (string) $user->getPostalCode();
        if (!empty($user->getNeighborhood())) $userOrm->neighborhood = $user->getNeighborhood();
        if (!empty($user->getNumber())) $userOrm->number = (string) $user->getNumber();
        if (!empty($user->getComplement())) $userOrm->complement = $user->getComplement();
        if (!empty($user->getCity())) $userOrm->city = $user->getCity();
        if (!empty($user->getState())) $userOrm->state = $user->getState();
        if (!empty($user->getUserType())) $userOrm->userType = $user->getUserType()->getType();
        if (!empty($user->getPassword())) $userOrm->password = $user->getPassword();
        if (!empty($user->getChangePassword())) $userOrm->changePassword = $user->getChangePassword();
        if (!empty($user->getCodeValidation())) $userOrm->codeValidation = $user->getCodeValidation();
        if (!empty($user->getAcceptedTermsOfUse())) $userOrm->acceptedTermsOfUse = $user->getAcceptedTermsOfUse();
        if (!empty($user->getAcceptedCardTerms())) $userOrm->acceptedCardTerms = $user->getAcceptedCardTerms();
        if (!empty($user->getLongitude())) $userOrm->longitude = $user->getLongitude();
        if (!empty($user->getLatitude())) $userOrm->latitude = $user->getLatitude();
        if (!empty($user->getDeviceid())) $userOrm->deviceId = $user->getDeviceid();
        if (!empty($user->getOneSignalId())) $userOrm->oneSignalId = $user->getOneSignalId();
        if (!empty($user->getFinancialScore())) {
            $userOrm->financialScore = $user->getFinancialScore();
        }
        if (!empty($user->getCriminalProcessCount())) {
            $userOrm->criminalProcessCount = $user->getCriminalProcessCount();
        }
        if (!empty($user->getScoreLevel())) {
            $userOrm->scoreLevel = $user->getScoreLevel();
        }
        $userOrm->criminalProcessCount = $user->getCriminalProcessCount();
        $userOrm->CCBStatus = $user->getCCBStatus();
        $userOrm->status = $user->getStatus();
        $userOrm->passwordAttempt = $user->getPasswordAttempt();
        $userOrm->scoreReevaluationDate = $user->getScoreReevaluationDate();
        $userOrm->deactivationDate = $user->getDeactivationDate();
        $userOrm->deactivationStatus = $user->getDeactivationStatus();
        $userOrm->dueDay = $user->getDueDay();
        $userOrm->closingDay = $user->getClosingDay();

        $userOrm->gender = $user->getGender();
        $userOrm->birthDate = $user->getbirthDate();
        $userOrm->maritalStatus = $user->getMaritalStatus();
        $userOrm->issuingState = $user->getIssuingState();
        $userOrm->issuingAuthority = $user->getIssuingAuthority();
        $userOrm->fatherName = $user->getFatherName();
        $userOrm->motherName = $user->getMotherName();
        $userOrm->nationality = $user->getNationality();
        $userOrm->isPushNotificationEnabled = $user->getIsPushNotificationEnabled();
        $userOrm->isPromotionalNotificationEnabled = $user->getIsPromotionalNotificationEnabled();

        return $this->persist($userOrm)->toDomain();
    }

    public function getById(int $id, bool $loadRelationships = false): User
    {
        /** @var UserORM $userOrm */
        $userOrm = parent::getEntityById($id);
        if ($loadRelationships) {
            if ($userOrm->documents instanceof PersistentCollection) {
                $userOrm->documents->initialize();
            }
        }
        return $userOrm->toDomain();
    }

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }

    public function findOneBy(array $params, bool $loadRelationships = false): ?User
    {
        $userOrm = $this->repository->findOneBy(
            $params
        );

        if ($userOrm === null) {
            return null;
        }

        if ($loadRelationships) {
            if ($userOrm->documents instanceof PersistentCollection) {
                $userOrm->documents->initialize();
            }
        }
        return $userOrm->toDomain();
    }

    public function searchUsers(int $limit, int $offset, ?string $filter = null, ?string $field = null, ?bool $support = false): PaginatedEntities
    {
        $qb = $this->entityManager->createQueryBuilder();

        // Query para contar o total de registros sem LIMIT e OFFSET
        $countQb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(u.id)')
            ->from(UserORM::class, 'u');

        // Query para buscar os usuários paginados
        $qb->select([
            'u.id',
            'u.userType',
            'u.name',
            'u.cpf',
            'u.rg',
            'u.deviceId',
            'u.email',
            'u.phone',
            'u.street',
            'u.number',
            'u.complement',
            'u.neighborhood',
            'u.city',
            'u.state',
            'u.postalCode'
        ])
            ->from(UserORM::class, 'u')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        // Adiciona filtros nas duas queries
        if ($field) {
            $qb->andWhere("u.$field LIKE :$field")
                ->setParameter($field, "%$filter%");
            $countQb->andWhere("u.$field LIKE :$field")
                ->setParameter($field, "%$filter%");
        }

        // Executa a contagem total sem LIMIT e OFFSET
        $totalItems = (int) $countQb->getQuery()->getSingleScalarResult();

        // Executa a query paginada
        $query = $qb->getQuery();
        $users = $query->getResult();
        if ($support)
            return new PaginatedEntities(
                totalItems: $totalItems,
                items: array_map(
                    function (UserORM $user) {
                        return $user->toDomain();
                    },
                    $qb->select(['u'])->getQuery()->execute()
                )
            );
        else
            return new PaginatedEntities(
                totalItems: $totalItems,
                items: array_map(fn($user) => new UserDTO(
                    id: $user['id'],
                    userType: $user['userType'],
                    name: $user['name'],
                    cpf: $user['cpf'],
                    rg: $user['rg'],
                    deviceId: $user['deviceId'],
                    email: $user['email'],
                    phone: $user['phone'],
                    street: $user['street'],
                    number: $user['number'],
                    complement: $user['complement'],
                    neighborhood: $user['neighborhood'],
                    city: $user['city'],
                    state: $user['state'],
                    postalCode: $user['postalCode']
                ), $users)
            );
    }

    public function saveUserDocument(int $userId, UserDocument $document): UserDocument
    {
        // Busca o usuário
        $userOrm = parent::getEntityById($userId);

        // Busca documentos existentes do mesmo tipo e ativo
        $existingDocuments = $this->entityManager
            ->getRepository(UserDocumentORM::class)
            ->findBy([
                'user' => $userOrm,
                'type' => $document->getDocumentType(),
                'isActive' => true
            ]);

        // Desativa todos os documentos existentes do mesmo tipo
        foreach ($existingDocuments as $existingDoc) {
            $existingDoc->isActive = false;
            $this->entityManager->persist($existingDoc);
        }

        // Cria o novo documento
        $documentOrm = UserDocumentORM::fromDomain($document);
        $documentOrm->user = $userOrm;
        $documentOrm->type = $document->getDocumentType();
        $documentOrm->status = $document->getStatus();
        $documentOrm->isActive = true;

        $this->entityManager->persist($documentOrm);
        $this->entityManager->flush();

        return $documentOrm->toDomain();
    }
}
