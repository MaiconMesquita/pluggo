<?php

namespace App\Infra\ThirdParty\ClientRequest;

use App\Domain\Entity\HttpRequest;
use App\Infra\Controller\HttpMethods;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class GuzzleHttpAdapter implements ClientRequest
{

    public function __construct(private Client $requestClient)
    {
    }

    private function executeRequest($url, $method, $payload, $headers): array
    {
        $content = [
            'headers' => $headers
        ];

        if (!empty($payload)) {
            if (!empty($headers['Content-Type']) && $headers['Content-Type'] === 'application/x-www-form-urlencoded') {
                $content['body'] = http_build_query($payload);
            } elseif ($method === HttpMethods::GET->value) {
                $url .= '?' . http_build_query($payload);
            } else {
                $content['body'] = json_encode($payload);
            }
        }

        try {
            $response = $this->requestClient->request(
                $method,
                $url,
                $content
            );
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
        } catch (ClientException $ce) {
            $statusCode = $ce->getResponse()->getStatusCode();
            $body = $ce->getResponse()->getBody();
        } 

        return [
            'statusCode' => $statusCode,
            'body' => json_decode($body, true)
        ];
    }

    public function request(HttpRequest $httpRequest): HttpRequest
    {
        $result = $this->executeRequest(
            $httpRequest->getRequestUrl(),
            $httpRequest->getMethod(),
            $httpRequest->getRequestBody(),
            $httpRequest->getRequestHeaders()
        );
        $httpRequest->setStatusCode($result['statusCode']);
        $httpRequest->setResponseBody($result['body'] ?? []);
        return $httpRequest;
    }
}
