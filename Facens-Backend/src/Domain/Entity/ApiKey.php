<?php

namespace App\Domain\Entity;

final class ApiKey
{
    public function __construct(
        private string $description,
        private string $type,
        private ?string $id = null,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getType()
    {
        return $this->type;
    }
}
