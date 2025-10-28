<?php

namespace App\Domain\Entity\Service\SessionTokenService;

class SessionTokenServiceOutput
{
    public ?string $name;
    public string $type;
    public string $accessToken;
    public string $refreshToken;
    public int $expiresIn;
}