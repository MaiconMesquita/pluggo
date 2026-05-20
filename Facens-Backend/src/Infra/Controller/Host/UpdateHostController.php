<?php

namespace App\Infra\Controller\Host;

use App\Application\UseCase\UpdateHost\UpdateHostInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class UpdateHostController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $body): UpdateHostInput
    {
        if (
            empty($body['id'])
        ) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        // ⭐ Monta input
        $input = new UpdateHostInput();
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
