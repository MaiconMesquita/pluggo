<?php
namespace App\Application\Middleware;

use Exception;
use App\Domain\Entity\Auth;
use App\Infra\ThirdParty\JWT\JWT;
use App\Infra\Controller\HttpRequest;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\{ThirdPartyFactoryContract, RepositoryFactoryContract};

class BearerAuth
{
    private DriverRepositoryContract $driverRepository;
    private HostRepositoryContract $hostRepository;
    private JWT $jwt;

    /**
     * @param string[] $allowedTypes Lista de tipos aceitos para essa rota
     */
    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ThirdPartyFactoryContract $thirdPartyFactory,
        private array $allowedTypes = ['driver', 'host'],
    ) {
        $this->driverRepository          = $repositoryFactory->getDriverRepository();
        $this->hostRepository      = $repositoryFactory->getHostRepository();
        $this->jwt = $thirdPartyFactory->getJWT();
    }

    public function execute(HttpRequest $request)
    {
        if (empty($request->headers['Authorization']) && empty($request->headers['authorization'])) {
            throw new Exception('Unauthorized');
        }

        $authorization = !empty($request->headers['Authorization'])
            ? $request->headers['Authorization'][0]
            : $request->headers['authorization'][0];

        $exploded = explode(' ', $authorization);
        if (strtolower($exploded[0]) !== 'bearer') {
            throw new Exception('Invalid authorization header');
        }

        $token = $exploded[1];
        $tokenDecoded = $this->jwt->decode($token);

        $entityId = $tokenDecoded->data['uid'];
        $authType = $tokenDecoded->data['authType']; // 'user' | 'employee' | 'establishment' | 'supplier'

        // 🔒 Restrição por rota com allowedTypes
        if (!in_array($authType, $this->allowedTypes, true)) {
            throw new Exception("Unauthorized: This route allows only [" . implode(', ', $this->allowedTypes) . "]");
        }

        switch ($authType) {
            case 'driver':
                $driver = $this->driverRepository->getById($entityId);
                if (!$driver) {
                    throw new Exception('Unauthorized: Invalid driver');
                }

                $auth = new Auth(
                    driverId: $driver->getId(),
                    scopes: [],
                    timezone: "America/Sao_Paulo",
                    authType: 'driver'
                );
                break;

            case 'host':
                $host = $this->hostRepository->getById($entityId);
                if (!$host) {
                    throw new Exception('Unauthorized: Invalid User');
                }

                $auth = new Auth(
                    hostId: $host->getId(),
                    scopes: [],
                    timezone: "America/Sao_Paulo",
                    authType: 'host'
                );
                break;
            default:
                throw new Exception('Unauthorized: Unknown Auth Type');
        }

        $auth->login();
    }
}
