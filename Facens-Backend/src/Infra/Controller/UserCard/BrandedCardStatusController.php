<?php

namespace App\Infra\Controller\UserCard;

use App\Application\UseCase\BrandedCardStatus\BrandedCardStatusInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class BrandedCardStatusController implements Controller
{
    public function __construct(private $brandedCardUser) {}

    public function serialize(HttpRequest $request): BrandedCardStatusInput
    {
        $input = new BrandedCardStatusInput;

        if (isset($request->body['cardId'])) {
            $input->cardId = (int) $request->body['cardId'];
        } else {
            new InvalidDataException('cardId is required');
        }

        if (isset($request->body['status'])) {
            $input->status = (string) $request->body['status'];
        } else {
            new InvalidDataException('status is required');
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
