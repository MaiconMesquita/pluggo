<?php

namespace App\Application\Middleware;

use Exception;
use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\UserType;
use App\Domain\Exception\InvalidAuthException;
use App\Infra\Controller\HttpRequest;
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Domain\RepositoryContract\{UserRepositoryContract, ApiKeyRepositoryContract, DriverRepositoryContract};


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
        if (empty($request->headers['Authorization']) && empty($request->headers['authorization'])) throw new Exception('Unauthorized');

        if (empty($request->headers['Api-Key']) && empty($request->headers['api-key'])) {
            throw new Exception('Unauthorized');
        }

        if(! empty($request->headers['Api-Key'])){
            $apiKeyId =  $request->headers['Api-Key'][0] || $request->headers['api-key'][0];
            $apiKey = @$this->apiKeyRepository->getById($apiKeyId);
            if(!$apiKey)
            throw new Exception('Unauthorized');
        }else if(!empty($request->headers['Authorization'])){
        
        $authorization =  $request->headers['Authorization'][0] || $request->headers['authorization'][0];

        $exploded = explode(' ', $authorization);
        if (strtolower($exploded[0]) !== 'basic') throw new Exception('The Authorization must be Basic');

        $basicAuth = base64_decode($exploded[1]);
        $email = explode(':', $basicAuth)[0];
        $password = explode(':', $basicAuth)[1];

        $driver = $this->driverRepository->findOneBy(["email" => $email]);
        if (!$driver->passwordVerify((string) $password)) throw new InvalidAuthException();

        $auth = new Auth(
            driverId: $driver->getId(),
            scopes: [], // $user->getScopes(),
            timezone: "America/Sao_Paulo", // $user->getTimezone(),
            authType: 'driver'
        );

        $auth->login();
        }
    }
}
