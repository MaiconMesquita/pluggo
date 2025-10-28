<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\SendVerificationCodeGeneral\SendVerificationCodeGeneralInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class SendVerificationCodeGeneralController implements Controller
{
    public function __construct(
        private $sendVerificationCode,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {}

    public function serialize(array $body): SendVerificationCodeGeneralInput
    {
        $schema = [
            "type" => 'required',
            "entity"     => 'required',
            "channels" => 'required',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
            throw new InvalidDataException($requestValidator->getMessageError());
        if (empty($body['phone']) && empty($body['email']) && empty($body['deviceId'])) {
            throw new InvalidDataException('At least one of phone, deviceId or email is required');
        }

        // Validação do campo isUser
        $entity = $body['entity'] ?? null;
        if (!isset($entity)) {
            throw new InvalidDataException('the entity field is required.');
        }


        $input = new SendVerificationCodeGeneralInput();
        $input->phone = $body['phone'] ?? null;
        $input->deviceId = $body['deviceId'] ?? null;
        $input->email = $body['email'] ?? null;
        $input->type = $body['type'];
        $input->channels = $body['channels'];
        $input->entity = $entity;

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->sendVerificationCode->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
