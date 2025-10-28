<?php

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\DocumentType;
use App\Domain\Entity\ValueObject\UserType;
use DateTime;

class Driver
{
    private ?int $id = null;
    private string $name;
    private ?string $latitude = null;
    private ?string $longitude = null;
    private ?string $email;
    private ?string $phone = null;
    private ?string $password = null;
    private ?string $oneSignalId = null;
    private DateTime $createdAt;
    private ?DateTime $updatedAt = null;
    /** @var CardModel[] */
    private array $carModel = [];

    public static function create(
        string $name,
        string $phone,
        string $email,
        string $password,
    ): self {
        $instance = new self();
        $instance->name = $name;
        $instance->phone = $phone;
        $instance->email = $email;
        $instance->password = $password;

        return $instance;
    }

    public function passwordVerify(string $rawPassword): bool
    {
        return password_verify(
            $rawPassword,
            $this->password
        );
    }

    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'oneSignalId' => $this?->oneSignalId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getOneSignalId(): ?string
    {
        return $this->oneSignalId;
    }

    public function setOneSignalId(?string $oneSignalId): self
    {
        $this->oneSignalId = $oneSignalId;
        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
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

    /** @return CardModel[] */
    public function getCarModel(): array
    {
        return $this->carModel;
    }

    /** @param CardModel[] $documents */
    public function setCarModel(array $carModel): self
    {
        $this->carModel = $carModel;
        return $this;
    }

    public function addCardModel(CarModel $carModel): self
    {
        $this->carModel[] = $carModel;
        return $this;
    }

}
