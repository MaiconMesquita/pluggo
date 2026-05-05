<?php

namespace App\Infra\Controller\Spot;

use App\Application\UseCase\UpdateChargerSpot\UpdateChargerSpotInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class UpdateChargerSpotController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): UpdateChargerSpotInput
    {
        if (
            empty($body['id'])
        ) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        // ⭐ Monta input
        $input = new UpdateChargerSpotInput();
        $input->id = $body['id'];
        $input->name = $body['name'];
        $input->latitude = $body['latitude'];
        $input->longitude = $body['longitude'];
        $input->pricePerKwh = $body['pricePerKwh'] ?? null;
        $input->connectorType = $body['connectorType'] ?? null;

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
