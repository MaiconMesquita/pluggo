<?php

namespace App\Infra\Controller\Host;

use App\Application\UseCase\CreateChargerSpot\CreateChargerSpotInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class CreateChargerSpotController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): CreateChargerSpotInput
    {
        if (
            empty($body['name']) ||
            empty($body['latitude']) ||
            empty($body['longitude'])
        ) {
            throw new InvalidDataException("Campos obrigatórios: name, latitude, longitude");
        }

        // ⭐ Monta input
        $input = new CreateChargerSpotInput();
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
