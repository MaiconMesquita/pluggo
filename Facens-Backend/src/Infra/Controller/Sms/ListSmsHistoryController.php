<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\ListSmsHistory\ListSmsHistoryInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListSmsHistoryController implements Controller
{
    public function __construct(private $listSmsHistory) {}

    public function serialize(array $params): ListSmsHistoryInput
    {
        $input = new ListSmsHistoryInput;
        if (isset($fields['userId'])) {
            if (!is_numeric($fields['userId']) || intval($fields['userId']) != $fields['userId']) {
                throw new InvalidDataException('The userId must be an integer.');
            }
        
            $input->userId = (int) $fields['userId'];
        }

        if (isset($params['establishmentId'])) {
            if (!is_numeric($params['establishmentId']) || intval($params['establishmentId']) != $params['establishmentId']) {
                throw new InvalidDataException('The establishmentId must be an integer.');
            }            
            $input->establishmentId = (int) $params['establishmentId'];
        }

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

        if (isset($params['passwordReset'])) {
            if ($params['passwordReset'] != 'true' && $params['passwordReset'] != 'false') {
                throw new InvalidDataException('The passwordReset field must be a boolean (true or false).');
            }
            $input->passwordReset = $params['passwordReset'] == 'true' ? true : false;
        }

        if (isset($params['firstPassword'])) {
            if ($params['firstPassword'] != 'true' && $params['firstPassword'] != 'false') {
                throw new InvalidDataException('The firstPassword field must be a boolean (true or false).');
            }
            $input->firstPassword = $params['firstPassword'] == 'true' ? true : false;
        }

        if (isset($params['codeGeneration'])) {
            if ($params['codeGeneration'] != 'true' && $params['codeGeneration'] != 'false') {
                throw new InvalidDataException('The codeGeneration field must be a boolean (true or false).');
            }
            $input->codeGeneration = $params['codeGeneration'] == 'true' ? true : false;
        }

        if (isset($params['invitation'])) {
            if ($params['invitation'] != 'true' && $params['invitation'] != 'false') {
                throw new InvalidDataException('The invitation field must be a boolean (true or false).');
            }
            $input->invitation = $params['invitation'] == 'true' ? true : false;
        }

        if (isset($params['billingTransaction'])) {
            if ($params['billingTransaction'] != 'true' && $params['billingTransaction'] != 'false') {
                throw new InvalidDataException('The billingTransaction field must be a boolean (true or false).');
            }
            $input->billingTransaction = $params['billingTransaction'] == 'true' ? true : false;
        }

        if (isset($params['employeePersonalSms'])) {
            if ($params['employeePersonalSms'] != 'true' && $params['employeePersonalSms'] != 'false') {
                throw new InvalidDataException('The employeePersonalSms field must be a boolean (true or false).');
            }
            $input->employeePersonalSms = $params['employeePersonalSms'] == 'true' ? true : false;
        }
        if (isset($params['withdrawalNotification'])) {
            if ($params['withdrawalNotification'] != 'true' && $params['withdrawalNotification'] != 'false') {
                throw new InvalidDataException('The withdrawalNotification field must be a boolean (true or false).');
            }
            $input->withdrawalNotification = $params['withdrawalNotification'] == 'true' ? true : false;
        }

        if (isset($params['cardRequestConfirmation'])) {
            if ($params['cardRequestConfirmation'] != 'true' && $params['cardRequestConfirmation'] != 'false') {
                throw new InvalidDataException('The cardRequestConfirmation field must be a boolean (true or false).');
            }
            $input->cardRequestConfirmation = $params['cardRequestConfirmation'] == 'true' ? true : false;
        }
        
        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->listSmsHistory->execute(
                $this->serialize(
                    $httpRequest->params,
                )
            )
        );
    }
}
