<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\AcceptAndVerifyCode\AcceptAndVerifyCodeInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class AcceptAndVerifyCodeController implements Controller
{
    public function __construct(
        private $acceptAndVerifyCode,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body): AcceptAndVerifyCodeInput
    {
        $schema = [            
            "phone"           => 'nullable',
            "email"             => 'nullable',
            "deviceId"          => 'nullable',
            "code" => 'required',
            "type" => 'required',
            "isUser"     => 'required',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema)) 
        throw new InvalidDataException($requestValidator->getMessageError());

        if (empty($body['phone']) && empty($body['email']) && empty($body['deviceId'])) {
            throw new InvalidDataException('At least one of phone, deviceId or email is required');
        }

        $isUser = $body['isUser'];
        if (!is_bool(filter_var($isUser, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            throw new InvalidDataException('The isUser field must be a boolean (true or false).');
        }

        $input = new AcceptAndVerifyCodeInput();
        $input->phone = $body['phone'] ?? null;
        $input->deviceId = $body['deviceId'] ?? null;
        $input->email = $body['email'] ?? null;
        $input->isUser = $isUser;
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
