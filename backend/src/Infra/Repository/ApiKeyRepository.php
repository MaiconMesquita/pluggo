<?php

namespace App\Infra\Repository;

use App\Domain\Entity\ApiKey;
use App\Domain\RepositoryContract\ApiKeyRepositoryContract;
use App\Infra\Database\EntitiesOrm\{ApiKey as ApiKeyORM};
use Doctrine\ORM\EntityManagerInterface;

class ApiKeyRepository extends BaseRepository implements ApiKeyRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(ApiKeyORM::class)
        );
    }

    public function create(ApiKey $apiKey): ApiKey
    {

        $apiKeyOrm = ApiKeyORM::fromDomain($apiKey);

        return $this->persist($apiKeyOrm)->toDomain();
    }

    public function getById(string $id): ApiKey
    {
        return parent::getEntityById($id)->toDomain();
    }
}
