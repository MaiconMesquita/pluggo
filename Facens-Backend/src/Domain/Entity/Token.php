<?php

namespace App\Domain\Entity;

use App\Domain\Entity\DTO\TokenDTO;
use App\Domain\Entity\ValueObject\TokenType;
use App\Domain\Exception\NotAcceptableException;

final class Token
{
    public string $id;
    public TokenDTO $token;
    private TokenType $type;
    private ?string $code = null;
    private ?int $driverId = null;
    private ?int $hostId = null;

    public static function create(
        TokenDTO $token,
        TokenType $type,
        ?int $driverId = null,
        ?int $hostId = null,
        ?string $code = null
    ): self {
        // Verifica se apenas um dos IDs foi fornecido
        $ids = array_filter([$driverId, $hostId]);
        if (count($ids) !== 1) {
            throw new NotAcceptableException("Only one ID must be provided.");
        }

        $instance = new self();
        $instance->id = $token->id;
        $instance->type = $type;
        $instance->code = $code;
        $instance->driverId = $driverId;
        $instance->hostId = $hostId;

        return $instance;
    }

    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'driverId' => $this->driverId,
            'hostId' => $this->hostId,
            'code' => $this->code,
            'type' => $this->type,
        ];
    }

    // Getters and setters

    public function getDriverId(): ?int
    {
        return $this->driverId;
    }

    public function setDriverId(?int $driverId)
    {
        $this->driverId = $driverId;
        return $this;
    }

    public function getHostId(): ?int
    {
        return $this->hostId;
    }

    public function setHostId(?int $hostId)
    {
        $this->hostId = $hostId;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code)
    {
        $this->code = $code;
        return $this;
    }

    public function getTokenType(): ?TokenType
    {
        return $this->type;
    }

    public function setTokenType(?TokenType $type)
    {
        $this->type = $type;
        return $this;
    }
}
