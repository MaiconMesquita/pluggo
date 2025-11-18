<?php

namespace App\Domain\Entity;

use App\Infra\Controller\HttpMethods;
use DomainException;

final class HttpRequest
{

    const START_HTTP_SUCCESS = 200;
    const END_HTTP_SUCCESS = 299;

    const ALLOWED_METHODS = [
        HttpMethods::POST,
        HttpMethods::GET,
        HttpMethods::PUT,
        HttpMethods::DELETE
    ];

    private array $responseBody = [];
    private int $responseStatusCode;

    public function __construct(
        private string $requestUrl,
        private HttpMethods $requestMethod,
        private array $requestBody = [],
        private array $requestHeaders = [],
    ) {
        if (!in_array($requestMethod, self::ALLOWED_METHODS))
            throw new DomainException(
                'Request method isn`t allowed'
            );
    }

    public function setResponseBody(array $responseBody)
    {
        $this->responseBody = $responseBody;
    }

    public function getRequestBody(): array
    {
        return $this->requestBody;
    }

    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    public function getRequestHeaders(): array
    {
        return $this->requestHeaders;
    }

    public function addHeader(string $key, string $value)
    {
        $this->requestHeaders[$key] = $value;
    }

    public function getMethod(): string
    {
        return $this->requestMethod->value;
    }

    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    public function getStatusCode(): int
    {
        return $this->responseStatusCode;
    }

    public function setStatusCode(int $responseStatusCode)
    {
        $this->responseStatusCode = $responseStatusCode;
    }

    public function statusCodeIsBetweenErrorRange(): bool
    {
        return ($this->responseStatusCode < self::START_HTTP_SUCCESS ||
            $this->responseStatusCode > self::END_HTTP_SUCCESS);
    }
}
