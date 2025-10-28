<?php

namespace App\Infra\Factory\Contract;

use App\Domain\RepositoryContract\{
    DriverRepositoryContract,
    HostRepositoryContract,
    ApiKeyRepositoryContract,
    TokenRepositoryContract
};
use PhpParser\Node\Expr\FuncCall;

interface RepositoryFactoryContract
{
    public function getDriverRepository(): DriverRepositoryContract;
    public function getHostRepository(): HostRepositoryContract;
    public function getApiKeyRepository(): ApiKeyRepositoryContract;
    public function getTokenRepository(): TokenRepositoryContract;
}
