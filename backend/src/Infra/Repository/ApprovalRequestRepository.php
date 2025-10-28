<?php

namespace App\Infra\Repository;

use App\Domain\Entity\ApprovalRequest;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\ApprovalRequestRepositoryContract;
use App\Infra\Database\EntitiesOrm\{
    ApprovalRequest as ApprovalRequestORM,
    BrandedCardRequest as BrandedCardRequestORM,
    DocumentReevaluationRequest as DocumentReevaluationRequestORM
};
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class ApprovalRequestRepository extends BaseRepository implements ApprovalRequestRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(ApprovalRequestORM::class)
        );
    }

    // ---------------- Create ----------------
    public function create(ApprovalRequest $request): ApprovalRequest
    {
        $orm = ApprovalRequestORM::fromDomain($request);
        $this->entityManager->persist($orm);
        $this->entityManager->flush(); // ID agora existe
        return $this->persist($orm)->toDomain();
    }

    // ---------------- Update ----------------
    public function update(ApprovalRequest $request): ApprovalRequest
    {
        /** @var ApprovalRequestORM $orm */
        $orm = parent::getEntityById($request->getId());

        $orm->status = $request->getStatus();
        $orm->approvedBy = $request->getApprovedBy();
        $orm->approvedAt = $request->getApprovedAt();
        $orm->notes = $request->getNotes();
        $orm->updatedAt = $request->getUpdatedAt() ?? new \DateTime();

        return $this->persist($orm)->toDomain();
    }

    public function getChildrenByApprovalId(int $approvalId): array
    {
        /** @var ApprovalRequestORM|null $orm */
        $orm = $this->repository->find($approvalId);
        if (!$orm) {
            return [];
        }

        $children = [];

        // 🔹 BrandedCardRequest (OneToOne)
        if ($orm->brandedCardRequest) {
            $children[] = $orm->brandedCardRequest->toDomain();
        }

        // 🔹 DocumentReevaluationRequest (OneToOne)
        if ($orm->documentReevaluationRequest) {
            $children[] = $orm->documentReevaluationRequest->toDomain();
        }

        // 🔹 Caso adicione novos tipos no futuro
        // if ($orm->outroTipoDeRequest) { ... }

        return $children;
    }



    // ---------------- Get by ID ----------------
    public function getById(int $id, bool $loadSubEntities = false): ApprovalRequest
    {
        /** @var ApprovalRequestORM $orm */
        $orm = parent::getEntityById($id);

        if ($loadSubEntities) {
            if ($orm->brandedCardRequest instanceof PersistentCollection) {
                $orm->brandedCardRequest->initialize();
            }
            if ($orm->documentReevaluationRequest instanceof PersistentCollection) {
                $orm->documentReevaluationRequest->initialize();
            }
        }

        return $orm->toDomain();
    }

    // ---------------- Find one by criteria ----------------
    public function findOneBy(array $params, bool $loadSubEntities = false): ?ApprovalRequest
    {
        $orm = $this->repository->findOneBy($params);
        if (!$orm) return null;

        if ($loadSubEntities) {
            if ($orm->brandedCardRequest instanceof PersistentCollection) {
                $orm->brandedCardRequest->initialize();
            }
            if ($orm->documentReevaluationRequest instanceof PersistentCollection) {
                $orm->documentReevaluationRequest->initialize();
            }
        }

        return $orm->toDomain();
    }

    // ---------------- Pagination ----------------

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities
    {
        return new PaginatedEntities(
            totalItems: $this->repository->count(criteria: $params),
            items: $this->getAll(params: $params, limit: $limit, offset: $offset)
        );
    }


    // ---------------- Approve / Reject ----------------
    public function approveRequest(int $requestId, int $approverId, ?string $notes = null): ApprovalRequest
    {
        $request = $this->getById($requestId);
        $request->approve($approverId, $notes);
        return $this->update($request);
    }

    public function rejectRequest(int $requestId, int $approverId, ?string $notes = null): ApprovalRequest
    {
        $request = $this->getById($requestId);
        $request->reject($approverId, $notes);
        return $this->update($request);
    }
}
