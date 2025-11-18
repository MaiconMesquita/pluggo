<?php

namespace App\Infra\Controller\Employee;

use App\Application\UseCase\ListEmployeeEstablishment\ListEmployeeEstablishmentInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListEmployeeEstablishmentController implements Controller
{
    public function __construct(private $listEmployeeEstablishment) {}

    public function serialize(array $params): ListEmployeeEstablishmentInput
    {
        $input = new ListEmployeeEstablishmentInput;
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
        if (isset($params['establishmentId'])) {
            if (!is_numeric($params['establishmentId']) || intval($params['establishmentId']) != $params['establishmentId']) {
                throw new InvalidDataException('The establishmentId must be an integer.');
            }   
            $input->establishmentId = (int) $params['establishmentId'];
        }

        if (isset($params['employeeId'])) {
            if (!is_numeric($params['employeeId']) || intval($params['employeeId']) != $params['employeeId']) {
                throw new InvalidDataException('The employeeId must be an integer.');
            }   
            $input->employeeId = (int) $params['employeeId'];
        }

        if (!empty($params['establishmentOwnerStatus'])) {
            if ($params['establishmentOwnerStatus'] != 'true' && $params['establishmentOwnerStatus'] != 'false') {
                throw new InvalidDataException('The establishmentOwnerStatus field must be a boolean (true or false).');
            }
            $input->establishmentOwnerStatus = $params['establishmentOwnerStatus'] == 'true' ? 1 : 0;
        }

        if (!empty($params['isSupplierEmployee'])) {
            if ($params['isSupplierEmployee'] != 'true' && $params['isSupplierEmployee'] != 'false') {
                throw new InvalidDataException('The isSupplierEmployee field must be a boolean (true or false).');
            }
            $input->isSupplierEmployee = $params['isSupplierEmployee'] == 'true' ? 1 : 0;
        }
        
        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->listEmployeeEstablishment->execute(
                $this->serialize(
                    $httpRequest->params,
                )
            )
        );
    }
}
