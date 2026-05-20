<?php

namespace App\Infra\Controller\Review;

use App\Application\UseCase\CreateReview\CreateReviewInput;
use App\Domain\Entity\Auth;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class CreateReviewController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): CreateReviewInput
    {
        if (empty($body['spotId']) || empty($body['rating'])) {
            throw new InvalidDataException("spotId e rating são obrigatórios");
        }

        $auth = Auth::getLogged();
        $authType = $auth->getAuthType();

        $input = new CreateReviewInput();
        $input->chargeSpotId = $body['spotId'];
        $input->rating = $body['rating'];
        $input->comment = $body['comment'] ?? null;

        if ($authType === 'driver') {
            $input->driverId = $auth->getDriver();
        } elseif ($authType === 'employee') {
            if (empty($body['driverId'])) {
                throw new InvalidDataException("driverId é obrigatório");
            }
            $input->driverId = $body['driverId'];
        } else {
            throw new InvalidDataException("Tipo de autenticação não suportado");
        }

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
