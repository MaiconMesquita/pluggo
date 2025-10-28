<?php

namespace App\Infra\ThirdParty\ClientRequest;

use App\Domain\Entity\HttpRequest;

interface ClientRequest
{
    public function request(HttpRequest $httpRequest): HttpRequest;
}
