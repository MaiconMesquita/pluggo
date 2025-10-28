<?php

namespace App\Infra\Repository;

use App\Domain\Entity\DocumentReevaluationRequest as DomainDocumentReevaluationRequest;
use App\Domain\RepositoryContract\DocumentReevaluationRepositoryContract;
use App\Infra\Database\EntitiesOrm\DocumentReevaluationRequest as DocumentReevaluationRequestORM;
use Doctrine\ORM\EntityManagerInterface;

class DocumentReevaluationRequestRepository extends BaseRepository implements DocumentReevaluationRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(DocumentReevaluationRequestORM::class)
        );
    }

    public function create(DomainDocumentReevaluationRequest $domain): DomainDocumentReevaluationRequest
    {
        $orm = DocumentReevaluationRequestORM::fromDomain($domain);
        return $this->persist($orm)->toDomain();
    }

    public function update(DomainDocumentReevaluationRequest $domain): DomainDocumentReevaluationRequest
    {
        /** @var DocumentReevaluationRequestORM $orm */
        $orm = parent::getEntityById($domain->getId());

        $orm->userId = $domain->getUserId();
        $orm->documents = $domain->getDocuments();

        $this->persist($orm);
        return $orm->toDomain();
    }

    public function getById(int $id, bool $loadSubEntities = false): ?DomainDocumentReevaluationRequest
    {
        /** @var DocumentReevaluationRequestORM $orm */
        $orm = parent::getEntityById($id);

        // Se precisar inicializar subentidades
        if ($loadSubEntities) {
            // Ex: se tiver algum relacionamento
            // $orm->relatedEntities->initialize();
        }

        return $orm?->toDomain();
    }


    public function findOneBy(array $criteria): ?DomainDocumentReevaluationRequest
    {
        /** @var DocumentReevaluationRequestORM|null $orm */
        $orm = $this->repository->findOneBy($criteria);
        return $orm ? $orm->toDomain() : null;
    }

    public function getAll(int $limit, int $offset, array $params = [], ?array $orderBy = null): array
    {
        return parent::getAll($limit, $offset, $params, $orderBy);
    }
}
