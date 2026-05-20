<?php

namespace App\Infra\Controller\Employee;

use App\Application\UseCase\DeleteDriver\DeleteDriverInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class DeleteDriverController implements Controller
{
    public function __construct(
        private $useCase
    ) {}

    public function serialize(array $params): DeleteDriverInput
    {
        if (empty($params['id'])) {
            throw new InvalidDataException("Campos obrigatórios: id");
        }

        $input = new DeleteDriverInput();
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
