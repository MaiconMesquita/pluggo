<?php

namespace App\Infra\Controller\Host;

use App\Application\UseCase\ListChargeSpots\ListChargeSpotsInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class ListChargeSpotsController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(HttpRequest $httpRequest): ListChargeSpotsInput
    {
        $input = new ListChargeSpotsInput();
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
