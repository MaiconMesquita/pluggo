<?php

namespace App\Infra\Controller\Employee;

use App\Application\UseCase\CreateRepresentativeEmployee\CreateRepresentativeEmployeeInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class CreateRepresentativeEmployeeController implements Controller
{
    public function __construct(
        private $createRepresentativeEmployee,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body): CreateRepresentativeEmployeeInput
    {
        $schema = [
            "name"    => 'required',
            "email" => 'required',
            "phone"    => 'required',
            "password"       => 'nullable',
            "cpf"       => 'nullable',
            "poloId"       => 'nullable',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
        throw new InvalidDataException($requestValidator->getMessageError());        

        $input = new CreateRepresentativeEmployeeInput;   

        $phone = $requestValidator->getParam('phone');
        if (!ctype_digit($phone))throw new InvalidDataException('Phone must contain only numbers.');
        $input->phone = $phone;            

        $input->name = $requestValidator->getParam('name');
        $input->email = $requestValidator->getParam('email');
        $input->cpf = $requestValidator->getParam('cpf');
        $input->poloId = $requestValidator->getParam('poloId');
        $input->password = $requestValidator->getParam('password');

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->createRepresentativeEmployee->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
