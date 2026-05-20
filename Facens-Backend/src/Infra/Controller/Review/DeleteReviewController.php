<?php

namespace App\Infra\Controller\Review;

use App\Application\UseCase\DeleteReview\DeleteReviewInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class DeleteReviewController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): DeleteReviewInput
    {
        if (empty($body['id'])) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        $input = new DeleteReviewInput();
        $input->id = $body['id'];

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
