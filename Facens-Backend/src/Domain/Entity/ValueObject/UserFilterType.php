<?php

namespace App\Domain\Entity\ValueObject;

use DomainException;

final class UserFilterType
{
    const ID = 'id';
    const PHONE = 'phone';
    const CPF = 'cpf';
    const EMAIL = 'email';
    const DEVICE_ID = 'deviceId';

    const ALL_USER_FILTER_TYPES = [
        self::ID,
        self::PHONE,
        self::CPF,
        self::EMAIL,
        self::DEVICE_ID,
    ];

    public function __construct(
        private string $userFilterType,
    ) {
        if (!in_array($this->userFilterType, self::ALL_USER_FILTER_TYPES))
            throw new DomainException('Invalid user filter type');
    }

    public function getType(): string
    {
        return $this->userFilterType;
    }
}