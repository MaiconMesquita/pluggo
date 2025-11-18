<?php

namespace App\Infra\Controller\UserCard;

use App\Application\UseCase\BrandedCardLimit\BrandedCardLimitInput;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class BrandedCardLimitController implements Controller
{
    public function __construct(private $brandedCardUser) {}

    public function serialize(HttpRequest $request): BrandedCardLimitInput
    {
        $input = new BrandedCardLimitInput;

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
