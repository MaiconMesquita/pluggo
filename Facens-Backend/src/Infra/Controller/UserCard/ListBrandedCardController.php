<?php

namespace App\Infra\Controller\UserCard;

use App\Application\UseCase\ListBrandedCard\ListBrandedCardInput;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListBrandedCardController implements Controller
{
    public function __construct(private $listBrandedCardUser) {}

    public function serialize(HttpRequest $request): ListBrandedCardInput
    {
        $input = new ListBrandedCardInput;

        // Pega userId do path
        if (isset($request->args['userId'])) {
            $input->userId = (int) $request->args['userId'];
        }

        return $input;
    }

    public function handle(HttpRequest $request): HttpResponse
    {
        $cards = $this->listBrandedCardUser->execute(
            $this->serialize($request) // <-- aqui
        );

        // Transforma cada objeto BrandedCard em array usando toJSON
        $cardsArray = array_map(fn($card) => $card->toJSON(), $cards);

        return new HttpResponse(HttpResponse::HTTP_SUCCESS_CODE, $cardsArray);
    }
}
