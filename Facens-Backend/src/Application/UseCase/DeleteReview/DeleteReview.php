<?php

namespace App\Application\UseCase\DeleteReview;

use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\SpotReviewRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class DeleteReview
{
    private SpotReviewRepositoryContract $reviewRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->reviewRepository = $repositoryFactory->getReviewSpotRepository();
    }

    public function execute(DeleteReviewInput $input): void
    {
        $review = $this->reviewRepository->getById($input->id);

        if (!$review) {
            throw new InvalidDataException("Review not found.");
        }

        $this->reviewRepository->delete($input->id);
    }
}
