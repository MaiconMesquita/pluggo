<?php

namespace App\Domain\Entity\ValueObject;

use DomainException;

final class TokenType
{
    const REFRESH_TOKEN = 'refreshToken';
    const API_TOKEN_CREDIFY = 'apiTokenCredify';
    const ACTIVATION_ACCOUNT = 'activationAccount';
    const CODE = 'code';

    const ALL_TOKEN = [
        self::REFRESH_TOKEN,
        self::ACTIVATION_ACCOUNT,
        self::API_TOKEN_CREDIFY,
        self::CODE,
    ];

    public function __construct(
        private string $tokenType,
    ) {
        if (!in_array($this->tokenType, self::ALL_TOKEN))
            throw new DomainException('Invalid token type');
    }

    public function getType(): string
    {
        return $this->tokenType;
    }
}
