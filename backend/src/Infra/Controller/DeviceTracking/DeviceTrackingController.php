<?php

namespace App\Infra\Controller\DeviceTracking;

use App\Application\UseCase\DeviceTracking\DeviceTrackingInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class DeviceTrackingController implements Controller
{
    public function __construct(
        private $resetDeviceId,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {}

    public function serialize(array $body): DeviceTrackingInput
    {
        $schema = [
            "deviceId"    => 'required',
            "email"    => 'nullable',
            "entity"    => 'required',
            "document" => 'nullable',
            "oneSignalId"       => 'nullable',
            "deviceModel" => 'nullable',
            "deviceType" => 'nullable',
            "os" => 'nullable',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
            throw new InvalidDataException($requestValidator->getMessageError());

        // Validação do campo isUser
        $entity = $body['entity'] ?? null;
        if (!isset($entity)) {
            throw new InvalidDataException('the entity field is required.');
        }

        $document = $body['document'] ?? null;

        $input = new DeviceTrackingInput;
        $input->document = $document;
        $input->entity = $entity;
        $input->email = $body['email'];
        $input->deviceId = $body['deviceId'];
        $input->oneSignalId = $body['oneSignalId'] ?? null;
        $input->deviceModel = $body['deviceModel'] ?? null;
        $input->deviceType = $body['deviceType'] ?? null;
        $input->os = $body['os'] ?? null;

        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->resetDeviceId->execute(
                $this->serialize(
                    $httpRequest->body,
                )
            )
        );
    }
}
