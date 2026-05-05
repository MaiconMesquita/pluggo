<?php

namespace App\Application\UseCase\UpdateReview;

class UpdateReviewInput
{
    public int $id;

    public ?int $rating = null;

    public ?string $comment = null;

}