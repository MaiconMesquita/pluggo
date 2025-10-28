<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\AcceptAndVerifyCodeGeneral\AcceptAndVerifyCodeGeneralInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class AcceptAndVerifyCodeGeneralController implements Controller
{
    public function __construct(
        private $acceptAndVerifyCode,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {}

    public function serialize(array $body): AcceptAndVerifyCodeGeneralInput
    {
        $schema = [
            "phone"           => 'nullable',
            "email"             => 'nullable',
            "deviceId"          => 'nullable',
            "code" => 'required',
            "type" => 'required',
            "entity"     => 'required',
            'channel' => 'required',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
            throw new InvalidDataException($requestValidator->getMessageError());

        if (empty($body['phone']) && empty($body['email']) && empty($body['deviceId'])) {
            throw new InvalidDataException('At least one of phone, deviceId or email is required');
        }

        if (empty($body['entity'])) {
            throw new InvalidDataException('Entity is required');
        }



        $input = new AcceptAndVerifyCodeGeneralInput();
        $input->phone = $body['phone'] ?? null;
        $input->deviceId = $body['deviceId'] ?? null;
        $input->email = $body['email'] ?? null;
        $input->entity = $body['entity'];
        $input->channel = $body['channel'];
        $input->code = $body['code'];
        $input->type = $body['type'];

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->acceptAndVerifyCode->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
