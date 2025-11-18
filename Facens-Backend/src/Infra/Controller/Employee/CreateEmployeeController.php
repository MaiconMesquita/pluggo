<?php

namespace App\Infra\Controller\Employee;

use App\Application\UseCase\CreateEmployee\CreateEmployeeInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class CreateEmployeeController implements Controller
{
    public function __construct(
        private $createEmployee,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body): CreateEmployeeInput
    {
        $schema = [
            "name"    => 'required',
            "email" => 'required',
            "phone"    => 'required',
            "employeeType"       => 'required',
            "password"       => 'nullable',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
        throw new InvalidDataException($requestValidator->getMessageError());        

        $input = new CreateEmployeeInput;   

        $phone = $requestValidator->getParam('phone');
        if (!ctype_digit($phone))throw new InvalidDataException('Phone must contain only numbers.');
        $input->phone = $phone;            

        $input->name = $requestValidator->getParam('name');
        $input->email = $requestValidator->getParam('email');
        
        $input->password = $requestValidator->getParam('password');
        
        $input->employeeType = $requestValidator->getParam('employeeType');

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->createEmployee->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
