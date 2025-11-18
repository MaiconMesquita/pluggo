<?php

namespace App\Infra\Database\Fixtures;

use App\Infra\Database\EntitiesOrm\ApiKey;

use Doctrine\ORM\EntityManagerInterface;

class CreateRootFixture extends FixtureContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    public function getPriority(): int
    {
        return 0;
    }

    public function executeInProd(): bool
    {
        return true;
    }

    public function execute()
    {
        $this->entityManager->beginTransaction();

        try {

            $apiKey = new ApiKey;
            $apiKey->description = 'Root ApiKey';
            $apiKey->type = 'admin';
            $this->entityManager->persist($apiKey);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $th) {
            $this->entityManager->rollback();
        }
    }
}
