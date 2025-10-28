<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\SendFirstPassword\SendFirstPasswordInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;

class SendFirstPasswordController implements Controller
{
    public function __construct(
        private $createSms,
    ) {
    }

    public function serialize(array $body): SendFirstPasswordInput
    {
        
        if (!isset($body['deviceId'])) {
            throw new InvalidDataException('deviceId is required');
        }

        $fields = [
            'phone' => $body['phone'] ?? null,
            'email' => $body['email'] ?? null,
        ];

        $filledFields = array_filter($fields);
        
        if (count($filledFields) > 2) {
            throw new InvalidDataException('To perform the search you can only enter one field.');
        }

        $input = new SendFirstPasswordInput();
        if (isset($fields['phone'])) {
            $input->filter = $fields['phone'];
            $input->filterType = 'phone';
        } 
        elseif (isset($fields['email'])) {
            $input->filter = $fields['email'];
            $input->filterType = 'email';
        }
        $input->phone = $body['phone'] ?? null;
        $input->email = $body['email'] ?? null;
        $input->deviceId = $body['deviceId'] ?? null; // Permitir null caso não seja enviado

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
       try {
        $this->createSms->execute(
            $this->serialize($httpRequest->body)
        );

        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            null 
        );
    } catch (\App\Domain\Exception\InvalidDataException $e) {
        return new HttpResponse(
            HttpResponse::HTTP_BLOCKING_BY_SCORE_EXCEPTION,
            ['error' => $e->getMessage()]
        );
    }
    }
}
