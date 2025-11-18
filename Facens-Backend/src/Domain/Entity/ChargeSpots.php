<?php

namespace App\Domain\Entity;

use DateTime;

/**
 * Entidade para transição entre Partner e Frontend
 * Contém todas as informações do cartão para comunicação com o parceiro
 */
class ChargeSpots
{
    private ?int $id = null;
    private string $name;
    private string $latitude;
    private string $longitude;
    private Host $host;
    private ?float $pricePerKwh = null;         // preço por kWh
    private array $reviews = [];                 // avaliações dos pontos
    private ?string $connectorType = null;       // tipo de conector
    private string $status = 'available';        // status: 'available' ou 'occupied'
    private DateTime $createdAt;
    private ?DateTime $deactivationDate = null;
    private ?DateTime $updatedAt = null;

    public static function create(
        Host $host,
        string $name,
        string $latitude,
        string $longitude,
        ?float $pricePerKwh = null,
        ?string $connectorType = null,
        string $status = 'available',
    ): self {
        $instance = new self();
        $instance->host = $host;
        $instance->name = $name;
        $instance->latitude = $latitude;
        $instance->longitude = $longitude;
        $instance->pricePerKwh = $pricePerKwh;
        $instance->connectorType = $connectorType;
        $instance->status = $status;
        $instance->createdAt = new DateTime();

        return $instance;
    }

    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'hostId' => $this->host->getId(),
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'pricePerKwh' => $this->pricePerKwh,
            'reviews' => $this->reviews,
            'connectorType' => $this->connectorType,
            'status' => $this->status,
            'deactivationDate' => $this->deactivationDate?->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    // Getters and Setters


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getHost(): Host
    {
        return $this->host;
    }

    public function setHost(Host $host): void
    {
        $this->host = $host;
    }

    public function getPricePerKwh(): ?float
    {
        return $this->pricePerKwh;
    }

    public function setPricePerKwh(?float $pricePerKwh): void
    {
        $this->pricePerKwh = $pricePerKwh;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }

    public function setReviews(array $reviews): void
    {
        $this->reviews = $reviews;
    }

    public function getConnectorType(): ?string
    {
        return $this->connectorType;
    }

    public function setConnectorType(?string $connectorType): void
    {
        $this->connectorType = $connectorType;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getDeactivationDate(): ?DateTime
    {
        return $this->deactivationDate;
    }

    public function setDeactivationDate(?DateTime $deactivationDate): void
    {
        $this->deactivationDate = $deactivationDate;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
