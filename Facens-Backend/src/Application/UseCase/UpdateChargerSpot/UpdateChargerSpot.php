<?php

namespace App\Application\UseCase\UpdateChargerSpot;

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

class UpdateChargerSpot
{
    private ChargeSpotsRepositoryContract $chargeSpotsRepository;
    private HostRepositoryContract $hostRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->chargeSpotsRepository = $repositoryFactory->getChargeSpotsRepository();
        $this->hostRepository = $repositoryFactory->getHostRepository();
    }

    public function execute(UpdateChargerSpotInput $input): ChargeSpots
    {


        // 3. CRIA A ENTIDADE DE DOMÍNIO
        $spot = $this->chargeSpotsRepository->getById($input->id, false);

        if(!$spot) {
            throw new InvalidDataException("Spot not found.");
        }

         // 2. atualiza somente o que veio no input
    if ($input->name !== null) {
        $spot->setName($input->name);
    }

    if ($input->latitude !== null) {
        $spot->setLatitude($input->latitude);
    }

    if ($input->longitude !== null) {
        $spot->setLongitude($input->longitude);
    }

    if ($input->pricePerKwh !== null) {
        $spot->setPricePerKwh($input->pricePerKwh);
    }

    if ($input->connectorType !== null) {
        $spot->setConnectorType($input->connectorType);
    }

    // 3. persiste alterações
    return $this->chargeSpotsRepository->update($spot);
    }
}
