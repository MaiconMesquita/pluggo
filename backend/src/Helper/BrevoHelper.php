<?php

namespace App\Helper;

use App\Domain\Entity\HttpRequest;
use App\Infra\ThirdParty\ClientRequest\ClientRequest;
use App\Infra\ThirdParty\Logging\Logging;

class BrevoHelper
{
    private string $baseUrl;
    private string $apiKey;
    private Logging $logging;
    private ClientRequest $clientRequest;

    public function __construct(ClientRequest $clientRequest, Logging $logging)
    {
        $this->clientRequest = $clientRequest;
        $this->logging = $logging;
        $this->baseUrl = rtrim($_ENV['BREVO_URL'] ?? 'https://api.brevo.com/v3', '/');
        $this->apiKey = $_ENV['BREVO_API_KEY'] ?? '';
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    // Método para construir URL completa
    public function buildUrl(string $path): string
    {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    public function doRequest(HttpRequest $httpRequest, string $action): HttpRequest
    {
        $startTime = microtime(true);

        // Adiciona APIKEY automaticamente no header
        $this->logging->info("[brevo_apiKey_$action]: " . $this->apiKey);

        $httpRequest->addHeader('api-key', $this->apiKey);

        $this->logging->info("[brevo_request_$action]: " . $httpRequest->getRequestUrl());
        $this->logging->info("[brevo_request_body_$action]: " . json_encode($httpRequest->getRequestBody()));
        $this->logging->info("[headers_to_send_$action]: " . json_encode($httpRequest->getRequestHeaders()));

        $response = $this->clientRequest->request($httpRequest);

        $this->logging->info("[brevo_response_$action]: statusCode: " . $response->getStatusCode() . " body: " . json_encode($response->getResponseBody()));
        $this->logging->info("[brevo_time_$action]: " . (microtime(true) - $startTime));

        if ($response->getStatusCode() >= 400) {
            // já vem logado acima
            return $response; // devolve mesmo assim, para a camada superior decidir
        }


        return $response;
    }
}
