<?php

namespace App\Infra\Controller\UserCard;

use App\Application\UseCase\BrandedCardDetails\BrandedCardDetailsInput;
use App\Application\UseCase\ListBrandedCard\ListBrandedCardInput;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class BrandedCardDetailsController implements Controller
{
    public function __construct(private $brandedCardUser) {}

    public function serialize(HttpRequest $request): BrandedCardDetailsInput
    {
        $input = new BrandedCardDetailsInput;

        // Pega userId do path
        if (isset($request->args['cardId'])) {
            $input->cardId = (int) $request->args['cardId'];
        }

        return $input;
    }

    public function handle(HttpRequest $request): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->brandedCardUser->execute(
                $this->serialize($request)
            )
        );
    }
}
