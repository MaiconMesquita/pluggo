<?php

namespace App\Infra\ThirdParty\JWT;


use App\Domain\Entity\TokenData;
use App\Domain\Entity\DTO\TokenDTO;

interface JWT
{
    public function encode(array $payload, ?int $tokenExpirationTime = null, ?string $key = null): TokenDTO;
    public function decode(string $rawToken, ?string $key = null): TokenData;
}
