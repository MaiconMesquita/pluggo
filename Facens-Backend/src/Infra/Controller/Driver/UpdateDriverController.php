<?php

namespace App\Infra\Controller\Driver;

use App\Application\UseCase\UpdateDriver\UpdateDriverInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class UpdateDriverController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): UpdateDriverInput
    {
        if (
            empty($body['id'])
        ) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        // ⭐ Monta input
        $input = new UpdateDriverInput();
        $input->id = $body['id'];
        $input->name = $body['name'];
        $input->phone = $body['phone'] ?? null;
        $input->email = $body['email'] ?? null;

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
