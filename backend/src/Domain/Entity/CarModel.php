<?php

namespace App\Domain\Entity;

use DateTime;

class CarModel
{
    private ?int $id = null;
    private string $model;
    private int $driverId;
    private DateTime $createdAt;
    private ?DateTime $updatedAt = null;

    public static function create(
        int $driverId,
        int $model,

    ): self {
        $instance = new self();
       
        $instance->driverId = $driverId;
        $instance->model = $model;

        return $instance;
    }

    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->driverId,
            'cardId' => $this->model,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDriverId(): ?int
    {
        return $this->driverId;
    }

    public function setDriverId(?int $driverId): self
    {
        $this->driverId = $driverId;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;
        return $this;
    }


    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
