<?php

namespace App\Domain\Entity;

use DateTime;

class ChargeSpot
{
    private ?int $id = null;
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
        string $latitude,
        string $longitude,
        ?float $pricePerKwh = null,
        ?string $connectorType = null,
        string $status = 'available',
    ): self {
        $instance = new self();
        $instance->connectorType = $connectorType;
        $instance->pricePerKwh = $pricePerKwh;
        $instance->status = $status;
        $instance->host = $host;
        $instance->latitude = $latitude;
        $instance->longitude = $longitude;

        return $instance;
    }


    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'hostId' => $this->host->getId(),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'pricePerKwh' => $this->pricePerKwh,
            'reviews' => $this->reviews,
            'connectorType' => $this->connectorType,
            'status' => $this->status,
            'deactivationDate' => $this->deactivationDate ? $this->deactivationDate->format('Y-m-d H:i:s') : null,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

      public function getPricePerKwh(): ?float { return $this->pricePerKwh; }
    public function setPricePerKwh(?float $pricePerKwh): void { $this->pricePerKwh = $pricePerKwh; }

    public function getReviews(): array { return $this->reviews; }
    public function setReviews(array $reviews): void { $this->reviews = $reviews; }
    public function addReview(float $review): void { $this->reviews[] = $review; }

    public function getConnectorType(): ?string { return $this->connectorType; }
    public function setConnectorType(?string $connectorType): void { $this->connectorType = $connectorType; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    // Retorna o ID do host (se existir)
    public function getHostId(): ?int
    {
        return $this->host?->getId();
    }

    // Atualiza o host a partir de um objeto Host
    public function setHost(Host $host): void
    {
        $this->host = $host;
    }


    public function getLatitude(): ?string
    {
        return $this->latitude;
    }
    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }
    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }
}
