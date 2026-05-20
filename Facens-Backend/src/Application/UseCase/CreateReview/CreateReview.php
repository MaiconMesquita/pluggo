<?php

namespace App\Application\UseCase\CreateReview;

use App\Domain\Entity\Auth;
use App\Domain\Entity\SpotReview;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\SpotReviewRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class CreateReview
{
    private SpotReviewRepositoryContract $reviewSpotsRepository;
    private DriverRepositoryContract $driverRepository;
    private ChargeSpotsRepositoryContract $chargeSpotsRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->reviewSpotsRepository = $repositoryFactory->getReviewSpotRepository();
        $this->driverRepository = $repositoryFactory->getDriverRepository();
        $this->chargeSpotsRepository = $repositoryFactory->getChargeSpotsRepository();
    }

    public function execute(CreateReviewInput $input): SpotReview
    {
        $auth = Auth::getLogged();
        $authType = $auth->getAuthType();

        if (!in_array($authType, ['driver', 'employee'], true)) {
            throw new InvalidDataException("Tipo de autenticação não suportado para criar reviews");
        }

        if (!$input->driverId) {
            throw new InvalidDataException("driverId não foi definido");
        }

        if ($authType === 'driver') {
            $this->validateDriverCreateOwnReview($auth, $input);
        }

        $driver = $this->driverRepository->getById($input->driverId);
        if (!$driver) {
            throw new InvalidDataException("Driver não encontrado");
        }

        $chargerSpot = $this->chargeSpotsRepository->getById($input->chargeSpotId);
        if (!$chargerSpot) {
            throw new InvalidDataException("ChargeSpot não encontrado");
        }

        $review = SpotReview::create(
            spot: $chargerSpot,
            driver: $driver,
            rating: $input->rating,
            comment: $input->comment,
        );

        return $this->reviewSpotsRepository->create($review);
    }

    private function validateDriverCreateOwnReview(Auth $auth, CreateReviewInput $input): void
    {
        $driverIdFromToken = $auth->getDriver();

        if ($driverIdFromToken !== $input->driverId) {
            throw new InvalidDataException("Driver pode apenas criar review para si mesmo");
        }
    }
}
