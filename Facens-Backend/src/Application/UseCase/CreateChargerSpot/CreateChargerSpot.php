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
        
    $host = $this->hostRepository->getById($input->hostId);

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
