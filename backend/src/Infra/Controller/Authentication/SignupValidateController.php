<?php

namespace App\Infra\Controller\Authentication;

use App\Application\UseCase\SignupValidate\SignupValidateInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class SignupValidateController implements Controller
{
    public function __construct(
        private $updateSignup,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body, array $args): SignupValidateInput
    {
        if (!isset($args['deviceId'])) throw new InvalidDataException('deviceId is required');
        
        // Validação inicial de campos obrigatórios
        $schema = [
            "password" => 'required',
            "isUser"   => 'required',
        ];
        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema)) 
            throw new InvalidDataException($requestValidator->getMessageError());
        
        // Validação do campo isUser
        $isUser = $body['isUser'] ?? null;
        if (!isset($isUser)) {
            throw new InvalidDataException('the isUser field is required.');
        }

        // Verifica se o campo isUser é 'true' ou 'false' como string
        if (!in_array($isUser, ['true', 'false'], true)) {
            throw new InvalidDataException('The isUser field must be a boolean (true or false).');
        }

        // Converte o valor de 'true' e 'false' para booleano
        $isUser = $isUser === 'true'; // Agora $isUser será um booleano

        // Valida campos adicionais dependendo do valor de isUser
        if ($isUser) {
            $cpf = $body['cpf'] ?? null;
            if (empty($cpf)) 
                throw new InvalidDataException('cpf is required');
        } else {
            $email = $body['email'] ?? null;
            if (empty($email)) 
                throw new InvalidDataException('email is required');
        }

        // Preenche o objeto de entrada com os dados validados
        $input = new SignupValidateInput();

        // Valida e atribui os valores dos termos
        $acceptedTermsOfUse = $body['acceptedTermsOfUse'] ?? null;
        if (isset($acceptedTermsOfUse)) {
            if (!in_array($acceptedTermsOfUse, ['true', 'false'], true)) {
                throw new InvalidDataException('The acceptedTermsOfUse field must be a boolean (true or false).');
            }
        }

        $acceptedAccreditationTerms = $body['acceptedAccreditationTerms'] ?? null;
        if (isset($acceptedAccreditationTerms)) {
            if (!in_array($acceptedAccreditationTerms, ['true', 'false'], true)) {
                throw new InvalidDataException('The acceptedAccreditationTerms field must be a boolean (true or false).');
            }
        }

        // Atribui os valores ao objeto de entrada
        $input->acceptedTermsOfUse = $acceptedTermsOfUse;
        $input->acceptedAccreditationTerms = $acceptedAccreditationTerms;
        $input->latitude = $body['latitude'] ?? null;
        $input->longitude = $body['longitude'] ?? null;
        $input->cpf = $body['cpf'] ?? null;
        $input->email = $body['email'] ?? null;
        $input->deviceId = $args['deviceId'];
        $input->password = $body['password'];
        $input->isUser = $isUser;
        
        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_CREATED,
            $this->updateSignup->execute(
                $this->serialize($httpRequest->body, $httpRequest->args)
            )
        );
    }
}
