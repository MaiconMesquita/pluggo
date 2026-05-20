<?php

namespace App\Infra\Controller\Host;

use App\Application\UseCase\DeleteHost\DeleteHostInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class DeleteHostController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $params): DeleteHostInput
    {
        if (empty($params['id'])) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        $input = new DeleteHostInput();
        $input->id = $params['id'];

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->useCase->execute(
                $this->serialize($httpRequest->params)
            )
        );
    }
}
