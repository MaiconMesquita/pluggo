<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\ApiKey;

interface ApiKeyRepositoryContract
{
    public function create(ApiKey $apiKey): ApiKey;

    public function getById(string $id): ApiKey;
}
