<?php

namespace App\Infra\Controller\Review;

use App\Application\UseCase\UpdateReview\UpdateReviewInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class UpdateReviewController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): UpdateReviewInput
    {
        if (
            empty($body['id'])
        ) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        // ⭐ Monta input
        $input = new UpdateReviewInput();
        $input->id = $body['id'];
        $input->rating = $body['rating'];
        $input->comment = $body['comment'];

        return $input;
    }
    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->useCase->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
