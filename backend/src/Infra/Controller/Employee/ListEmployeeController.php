<?php

namespace App\Infra\Controller\Employee;

use App\Application\UseCase\ListEmployee\ListEmployeeInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListEmployeeController implements Controller
{
    public function __construct(private $listEmployee) {}

    public function serialize(array $params): ListEmployeeInput
    {
        $input = new ListEmployeeInput;
        if (isset($params['limit'])) {
            if (!is_numeric($params['limit']) || intval($params['limit']) != $params['limit']) {
                throw new InvalidDataException('The limit must be an integer.');
            }
            $input->limit = (int) $params['limit'];
        }
        if (isset($params['offset'])) {
            if (!is_numeric($params['offset']) || intval($params['offset']) != $params['offset']) {
                throw new InvalidDataException('The offset must be an integer.');
            }   
            $input->offset = (int) $params['offset'];
        }
        if (isset($params['employeeId'])) {
            if (!is_numeric($params['employeeId']) || intval($params['employeeId']) != $params['employeeId']) {
                throw new InvalidDataException('The employeeId must be an integer.');
            }   
            $input->employeeId = (int) $params['employeeId'];
        }
        if (isset($params['employeeType'])) {
            $input->employeeType = $params['employeeType'];
        }

        $fields = [
            'phone' => $params['phone'] ?? null,
            'cpf' => $params['cpf'] ?? null,
            'email' => $params['email'] ?? null,
            'name' => $params['name'] ?? null,
        ];

        $filledFields =  array_filter($fields);
        
        if (count($filledFields) > 1) {
            throw new InvalidDataException('To perform the search you can only enter one field.');
        }

        if (isset($fields['phone'])) {
            $input->filter = $fields['phone'];
            $input->field = 'phone';
        } 

        elseif (isset($fields['name'])) {
            $input->filter = $fields['name'];
            $input->field = 'name';
        }
        
        elseif (isset($fields['cpf'])) {
            $input->filter = $fields['cpf'];
            $input->field = 'cpf';
        }

        elseif (isset($fields['email'])) {
            $input->filter = $fields['email'];
            $input->field = 'email';
        }
        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->listEmployee->execute(
                $this->serialize(
                    $httpRequest->params,
                )
            )
        );
    }
}
