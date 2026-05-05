<?php

namespace App\Application\UseCase\UpdateReview;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ChargeSpots;
use App\Domain\Entity\Host;
use App\Domain\Entity\ValueObject\EntityType;
use App\Domain\Entity\ValueObject\UserType;
use App\Domain\Exception\NotAcceptableException;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ChargeSpotsRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Domain\RepositoryContract\SpotReviewRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class UpdateReview
{
    private SpotReviewRepositoryContract $reviewSpotRepository;
    private HostRepositoryContract $hostRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->reviewSpotRepository = $repositoryFactory->getReviewSpotRepository();
        $this->hostRepository = $repositoryFactory->getHostRepository();
    }

    public function execute(UpdateReviewInput $input): void
    {


        // 3. CRIA A ENTIDADE DE DOMÍNIO
        $spot = $this->reviewSpotRepository->getById($input->id);

        if(!$spot) {
            throw new InvalidDataException("Spot not found.");
        }

         // 2. atualiza somente o que veio no input
    if ($input->rating !== null) {
        $spot->setRating($input->rating);
    }

    if ($input->comment !== null) {
        $spot->setComment($input->comment);
    }

    $this->reviewSpotRepository->update($spot);
    // 3. persiste alterações
    return ;
    }
}
