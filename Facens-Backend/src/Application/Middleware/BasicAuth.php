<?php

namespace App\Application\Middleware;

use Exception;
use App\Domain\Entity\Auth;
use App\Domain\Exception\InvalidAuthException;
use App\Infra\Controller\HttpRequest;
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Domain\RepositoryContract\{ApiKeyRepositoryContract, DriverRepositoryContract};


class BasicAuth
{
    private DriverRepositoryContract $driverRepository;
    private ApiKeyRepositoryContract $apiKeyRepository;

    public function __construct(RepositoryFactoryContract $repositoryFactory)
    {
        $this->driverRepository = $repositoryFactory->getDriverRepository();
        $this->apiKeyRepository = $repositoryFactory->getApiKeyRepository();
    }

    public function execute(HttpRequest $request)
    {
        $authorization = $request->headers['Authorization'][0] ?? $request->headers['authorization'][0] ?? null;
        $apiKey = $request->headers['Api-Key'][0] ?? $request->headers['api-key'][0] ?? null;

        if (!$authorization && !$apiKey) {
            throw new Exception('Unauthorized');
        }

        // Validar com Api-Key
        if ($apiKey) {
            $key = @$this->apiKeyRepository->getById($apiKey);
            if (!$key) {
                throw new Exception('Unauthorized');
            }
            return;
        }

        // Validar com Basic Auth
        if ($authorization) {
            $exploded = explode(' ', $authorization);
            if (strtolower($exploded[0]) !== 'basic') {
                throw new Exception('The Authorization must be Basic');
            }

            $basicAuth = base64_decode($exploded[1]);
            $credentials = explode(':', $basicAuth);
            $email = $credentials[0] ?? null;
            $password = $credentials[1] ?? null;

            if (!$email || !$password) {
                throw new Exception('Invalid credentials format');
            }

            $driver = $this->driverRepository->findOneBy(["email" => $email]);
            if (!$driver || !$driver->passwordVerify($password)) {
                throw new InvalidAuthException();
            }

            $auth = new Auth(
                driverId: $driver->getId(),
                scopes: [],
                timezone: "America/Sao_Paulo",
                authType: 'driver'
            );

            $auth->login();
        }
    }
}

