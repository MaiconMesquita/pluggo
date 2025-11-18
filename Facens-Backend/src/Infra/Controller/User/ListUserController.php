<?php

namespace App\Infra\Controller\User;

use App\Application\UseCase\ListUser\ListUserInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListUserController implements Controller
{
    public function __construct(private $listUser) {}

    public function serialize(array $params): ListUserInput
    {
        $input = new ListUserInput;
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
        
        $fields = [
            'name' => $params['name'] ?? null,
            'rg' => $params['rg'] ?? null,
            'phone' => $params['phone'] ?? null,
            'cpf' => $params['cpf'] ?? null,
            'email' => $params['email'] ?? null,
            'deviceId' => $params['deviceId'] ?? null,
        ];

        $filledFields = array_filter($fields);
        
        if (count($filledFields) > 1) {
            throw new InvalidDataException('To perform the search you can only enter one field.');
        }

        if (isset($fields['phone'])) {
            $input->filter = $fields['phone'];
            $input->field = 'phone';
        } 

        elseif (isset($fields['rg'])) {
            $input->filter = $fields['rg'];
            $input->field = 'rg';
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

        elseif (isset($fields['deviceId'])) {
            $input->filter = $fields['deviceId'];
            $input->field = 'deviceId';
        }
        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->listUser->execute(
                $this->serialize(
                    $httpRequest->params,
                )
            )
        );
    }
}
