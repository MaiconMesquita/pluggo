<?php

namespace App\Application\UseCase\CreateChargerSpot;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ChargeSpots;
use App\Domain\Entity\Host;
use App\Domain\Entity\ValueObject\EntityType;
use App\Domain\Entity\ValueObject\UserType;
use App\Domain\Exception\NotAcceptableException;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class CreateChargerSpot
{
    private ChargeSpotsRepositoryContract $chargeSpotsRepository;
    private HostRepositoryContract $hostRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->chargeSpotsRepository = $repositoryFactory->getChargeSpotsRepository();
        $this->hostRepository = $repositoryFactory->getHostRepository();
    }

    public function execute(CreateChargerSpotInput $input): ChargeSpots
    {
        // Obtém usuário logado
        $auth = Auth::getLogged();

        // 1. GARANTE QUE O USUÁRIO É DO TIPO HOST
        if ($auth->getAuthType() !== EntityType::HOST) {
            throw new NotAcceptableException("Apenas usuários do tipo Host podem criar pontos de carregamento.");
        }

        // 2. GARANTE QUE O HOST TEM HOST-ID VÁLIDO
        $hostId = $auth->getHost();
        if (!$hostId) {
            throw new InvalidDataException("HostId inválido para o usuário atual.");
        }

        // Cria uma instância de Host (somente com ID)
        $host = $this->hostRepository->getById($hostId);

        if (!$host) {
            throw new InvalidDataException("Host esta null.");
        }

        error_log("HOST: " . var_export($host, true));
        // 3. CRIA A ENTIDADE DE DOMÍNIO
        $spot = ChargeSpots::create(
            host: $host,
            name: $input->name,
            latitude: $input->latitude,
            longitude: $input->longitude,
            status: 'available'
        );

        // 4. PERSISTE NO BANCO VIA REPOSITÓRIO
        return $this->chargeSpotsRepository->create($spot);
    }
}
