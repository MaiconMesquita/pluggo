<?php

namespace App\Application\UseCase\ListCard;

use App\Domain\RepositoryContract\CardRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListCard
{
    private CardRepositoryContract $cardRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->cardRepository = $repositoryFactory->getCardRepository();        
    }

    public function execute(ListCardInput $input)
    { 

        $page = $this->cardRepository->getCardsWithSegments(
            limit: $input->limit,
            offset: $input->offset,
            isPrimaryCard: $input->isPrimaryCard,
            primaryCardId: $input->primaryCardId,
            segmentId: $input->segmentId,
            establishmentId: $input->establishmentId
        );

        return $page->toJSON();
    }
}
