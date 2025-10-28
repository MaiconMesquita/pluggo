<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\ResetPasswordForAccount\ResetPasswordForAccountInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class ResetPasswordForAccountController implements Controller
{
    public function __construct(
        private $resetPassword,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body): ResetPasswordForAccountInput
    {
        $schema = [
            "email"       => 'required',
            "isUser"    => 'required',
        ];
        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
        throw new InvalidDataException($requestValidator->getMessageError());

        $isUser = $body['isUser'];
        if (isset($isUser)) {
            if (!in_array($isUser, ['true', 'false'], true)) {
                throw new InvalidDataException('The isUser field must be a boolean (true or false).');
            }
        }

        $cpf = $body['cpf'] ?? null;
        $cnpj = $body['cnpj'] ?? null;
        $deviceId = $body['deviceId'] ?? null;

        // Validação adicional para `passwordReset`
        if ($isUser) {
            if (empty($cpf)) 
            throw new InvalidDataException('cpf is required');        
            if (empty($deviceId)) 
            throw new InvalidDataException('deviceId is required');              
        }

        if (isset($cpf) && (!ctype_digit($cpf) || strlen($cpf) !== 11)) 
        throw new InvalidDataException('CPF must contain exactly 11 digits.');      
    
        if (isset($cnpj) && (!ctype_digit($cnpj) || strlen($cnpj) !== 14)) 
        throw new InvalidDataException('CNPJ must contain exactly 14 digits.');       
        
        $input = new ResetPasswordForAccountInput;
        $input->cpf = $cpf;
        $input->cnpj = $cnpj;
        $input->deviceId = $deviceId;
        $input->isUser = $isUser;
        $input->email = $body['email'];
        
        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->resetPassword->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
