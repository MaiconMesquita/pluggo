<?php

namespace App\Infra\Controller\Spot;

use App\Application\UseCase\DeleteChargeSpot\DeleteChargeSpotInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class DeleteChargeSpotController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): DeleteChargeSpotInput
    {
        if (empty($body['id'])) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        $input = new DeleteChargeSpotInput();
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
