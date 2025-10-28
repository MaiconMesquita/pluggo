<?php

namespace App\Application\Middleware;

use App\Domain\RepositoryContract\ApiKeyRepositoryContract;
use App\Infra\Controller\HttpRequest;
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use Exception;

class ApiKeyAuth
{
    private ApiKeyRepositoryContract $apiKeyRepository;

    public function __construct(RepositoryFactoryContract $repositoryFactory)
    {
        $this->apiKeyRepository = $repositoryFactory->getApiKeyRepository();
    }

    public function execute(HttpRequest $request)
    {
        error_log('HEADERS RECEBIDOS: ' . json_encode($request->headers));

        if (! empty($request->headers['Api-Key']) || ! empty($request->headers['api-key'])) {
            $apiKey = ! empty($request->headers['Api-Key'])
                ? $request->headers['Api-Key'][0]
                : $request->headers['api-key'][0];
            $key = @$this->apiKeyRepository->getById($apiKey);
            if (empty($key)) {
                throw new Exception('Unauthorized');
            }
        } else {
            throw new Exception('Unauthorized');
        }
    }
}
