<?php

namespace App\Domain\Entity\ValueObject;

use DomainException;

final class EntityType
{
    const DRIVER = 'driver';
    const HOST = 'host';

    const ALL_USERS = [
        self::DRIVER,
        self::HOST,
    ];

    public function __construct(
        private string $entityType,
    ) {
        if (!in_array($this->entityType, self::ALL_USERS))
            throw new DomainException('Invalid entity type');
    }

    public function getType(): string
    {
        return $this->entityType;
    }
}
