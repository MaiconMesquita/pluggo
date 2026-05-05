<?php

namespace App\Domain\Entity;

use DateTime;

class SpotReview
{
    private ?int $id = null;
    private ChargeSpots $spot;
    private Driver $driver;
    private int $rating; // 1 a 5
    private ?string $comment = null;
    private DateTime $createdAt;

    public static function create(
        ChargeSpots $spot,
        Driver $driver,
        int $rating,
        ?string $comment = null
    ): self {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('Rating deve ser entre 1 e 5');
        }

        $instance = new self();
        $instance->spot = $spot;
        $instance->driver = $driver;
        $instance->rating = $rating;
        $instance->comment = $comment;
        $instance->createdAt = new DateTime();

        return $instance;
    }

    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'spotId' => $this->spot->getId(),
            'user' => [
                'id' => $this->driver->getId(),
                'name' => $this->driver->getName(),
            ],
            'rating' => $this->rating,
            'comment' => $this->comment,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    // GETTERS

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpot(): ChargeSpots
    {
        return $this->spot;
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    // SETTERS

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setSpot(ChargeSpots $spot): void
    {
        $this->spot = $spot;
    }

    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
    }

    public function setRating(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('Rating deve ser entre 1 e 5');
        }

        $this->rating = $rating;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
