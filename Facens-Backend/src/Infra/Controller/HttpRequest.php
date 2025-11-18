<?php

namespace App\Infra\Controller;

final class HttpRequest
{

    public function __construct(
        public ?array $body,
        public ?array $params,
        public array $headers,
        public string $route,
        public string $method = 'GET',
        public array $args = []
    ) {
        if (!$body) $this->body = [];
    }
}
