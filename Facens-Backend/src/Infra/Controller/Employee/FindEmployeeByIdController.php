<?php

namespace App\Infra\Controller\Employee;

use App\Application\UseCase\FindEmployeeById\FindEmployeeByIdInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class FindEmployeeByIdController implements Controller
{
    public function __construct(private $findEmployeeById) {}

    public function serialize(array $args): FindEmployeeByIdInput
    {
        $input = new FindEmployeeByIdInput;

        $id = (int) $args['id'];
        if (isset($id)) {
            if (!is_numeric($id) || intval($id) != $id) {
                throw new InvalidDataException('The id must be an integer.');
            }
            $input->id = $id;           
        }else{
            throw new InvalidDataException('The id field is mandatory.');
        }

        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->findEmployeeById->execute(
                $this->serialize(
                    $httpRequest->args,
                )
            )
        );
    }
}
