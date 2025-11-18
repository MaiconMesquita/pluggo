<?php

namespace App\Infra\Controller\Host;

use App\Application\UseCase\ListAllChargeSpots\ListAllChargeSpotsInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class ListAllChargeSpotsController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(HttpRequest $httpRequest): ListAllChargeSpotsInput
    {
        $input = new ListAllChargeSpotsInput();
        $input->hostId = $httpRequest->query['hostId'] ?? null;
        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        $spots = $this->useCase->execute(
            $this->serialize($httpRequest)
        );

        // Converte cada objeto ChargeSpots em array
        $spotsArray = array_map(fn($spot) => $spot->toJSON(), $spots);

        return new HttpResponse(HttpResponse::HTTP_SUCCESS_CODE, $spotsArray);
    }
}
