<?php

namespace App\Domain\Entity;

use DateTime;

class MessageHistory
{
    private ?int $id = null;
    private string $entityType;
    private int $entityId;
    private string $message;
    private DateTime $createdAt;

    public static function withParams(
        string $entityType,
        int $entityId,
        string $message,
    ): self {
        $instance = new self;
        $instance->entityType = $entityType;
        $instance->entityId = $entityId;
        $instance->message = $message;

        return $instance;
    }

    public function toJSON(): array
    {

        return  [
            "id" => $this->id,
            "entityType"        => $this->entityType,
            "entityId"   => $this->entityId,
            "message"      => $this->message,
            "createdAt" => $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null,
        ];
    }
  
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(?string $entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(?int $entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
