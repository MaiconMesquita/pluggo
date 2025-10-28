<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\ResetPasswordForAccountGeneral\ResetPasswordForAccountGeneralInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class ResetPasswordForAccountGeneralController implements Controller
{
    public function __construct(
        private $resetPassword,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body): ResetPasswordForAccountGeneralInput
    {
        $schema = [
            "email"       => 'required',
            "entity"    => 'required',
        ];
        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
        throw new InvalidDataException($requestValidator->getMessageError());

        $entity = $body['entity'];
        if (!isset($entity)) {
            throw new InvalidDataException('entity is required'); 
        }
        
        $input = new ResetPasswordForAccountGeneralInput;
        $input->entity = $entity;
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
