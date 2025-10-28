<?php

namespace App\Infra\ThirdParty\Sms;

use App\Infra\Controller\HttpMethods;
use App\Domain\Entity\ValueObject\{PhoneNumber, SmsType};
use App\Infra\ThirdParty\ClientRequest\ClientRequest;
use App\Domain\Entity\HttpRequest;
use App\Domain\Exception\PartnerException;

class ComTeleSmsAdapter implements Sms
{
    public function __construct(
        private ClientRequest $clientRequest,
    ) {}

    public function sendMessage(
        string $message,
        PhoneNumber $phoneNumber,
    ): array {
        $headers = [
            'content-type' => "application/json",
            'auth-key' => $_ENV['COM_TELE_API_KEY']
        ];
        $payload = [
            'Receivers' => $phoneNumber->getFullPhoneNumber(),
            'Content'   => $message
        ];

        $url = $_ENV['COM_TELE_URL'];
        $httpClient = new HttpRequest(
            $url,
            HttpMethods::POST,
            $payload,
            $headers
        );

        $httpClient = $this->clientRequest->request($httpClient);


        $responseBody = $httpClient->getResponseBody();

        if (!$responseBody['Success']) {
            throw new PartnerException("Error sending SMS", "comtele");
        }


        return [
            'url' => $url,
            'requestData' => $payload,
            'responseData' => $responseBody,
        ];
    }
}
