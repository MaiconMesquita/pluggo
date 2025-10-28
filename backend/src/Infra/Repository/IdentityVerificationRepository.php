<?php

namespace App\Infra\Repository;

use App\Domain\Entity\IdentityVerification;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\RepositoryContract\IdentityVerificationRepositoryContract;
use App\Infra\Database\EntitiesOrm\IdentityVerification as IdentityVerificationORM;
use Doctrine\ORM\EntityManagerInterface;

class IdentityVerificationRepository extends BaseRepository implements IdentityVerificationRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(IdentityVerificationORM::class)
        );
    }

    public function create(IdentityVerification $identityVerification): IdentityVerification
    {
        $orm = IdentityVerificationORM::fromDomain($identityVerification);
        return $this->persist($orm)->toDomain();
    }

    public function update(IdentityVerification $identityVerification): IdentityVerification
    {
        $orm = parent::getEntityById($identityVerification->getId());

        if ($identityVerification->getAcceptedTerms() !== null) {
            $orm->acceptedTerms = $identityVerification->getAcceptedTerms();
        }

        if (!empty($identityVerification->getUserId())) {
            $orm->userId = $identityVerification->getUserId();
        }

        if (!empty($identityVerification->getRawResponse())) {
            $orm->nuvideoResponse = $identityVerification->getRawResponse();
        }

        if ($identityVerification->getStatus() !== null) {
            $orm->status = $identityVerification->getStatus();
        }

        if ($identityVerification->getFraudScore() !== null) {
            $orm->fraudScore = $identityVerification->getFraudScore();
        }

        if ($identityVerification->getCreatedAt() !== null) {
            $orm->createdAt = $identityVerification->getCreatedAt();
        }

        if ($identityVerification->getUpdatedAt() !== null) {
            $orm->updatedAt = $identityVerification->getUpdatedAt();
        }

        return $this->persist($orm)->toDomain();
    }

    public function getById(int $id): IdentityVerification
    {
        return parent::getEntityById($id)->toDomain();
    }

    public function findOneBy(array $params): ?IdentityVerification
    {
        $orm = $this->repository->findOneBy($params);

        if ($orm === null) {
            return null;
        }

        return $orm->toDomain();
    }

    public function getAllPaginated(?int $limit = null, ?int $offset = null, ?array $params = []): PaginatedEntities
    {
        $criteria = $params ?? [];

        if ($limit > 0 && $offset >= 0) {
            return new PaginatedEntities(
                totalItems: $this->repository->count($criteria),
                items: $this->getAll(params: $criteria, limit: $limit, offset: $offset)
            );
        }

        return new PaginatedEntities(
            totalItems: $this->repository->count($criteria),
            items: $this->getByParams(params: $criteria)
        );
    }
}
