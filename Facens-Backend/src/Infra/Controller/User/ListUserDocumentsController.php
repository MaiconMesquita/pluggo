<?php

namespace App\Infra\Controller\User;

use App\Application\UseCase\ListUserDocuments\ListUserDocumentsInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListUserDocumentsController implements Controller
{
    public function __construct(private $listUserDocuments) {}

    public function serialize(array $args): ListUserDocumentsInput
    {
        $input = new ListUserDocumentsInput;

        if (!isset($args['userId'])) {
            throw new InvalidDataException('The userId is required.');
        }

        if (!is_numeric($args['userId']) || intval($args['userId']) != $args['userId']) {
            throw new InvalidDataException('The userId must be an integer.');
        }

        $input->userId = (int) $args['userId'];

        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->listUserDocuments->execute(
                $this->serialize(
                    $httpRequest->args,
                )
            )
        );
    }
}
