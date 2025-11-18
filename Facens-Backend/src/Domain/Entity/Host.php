<?php

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\EmployeeType;
use DateTime;

final class Host
{
    private ?int $id = null;
    private string $name;
    private string $email;
    private string $phone;
    private string $password;
    private DateTime $createdAt;
    private ?DateTime $deactivationDate = null;
    private ?DateTime $updatedAt = null;

    /** @var ChargeSpot[] */
    private array $chargeSpot = [];

    public static function create(
        string $name,
        string $email,
        string $phone,
        string $password,
    ): self {
        $instance = new self();
        $instance->name = $name;
        $instance->email = $email;
        $instance->phone = $phone;
        $instance->password = $password;

        return $instance;
    }

    public function passwordVerify(string $rawPassword): bool
    {
        return password_verify($rawPassword, $this->password);
    }

    public function toJSON(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'deactivationDate' => $this->deactivationDate?->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'chargeSpots' => array_map(fn($c) => $c->toJSON(), $this->chargeSpot),
        ];
    }

    // ----------- Getters e setters base ----------------
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): self { $this->email = $email; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): self { $this->password = $password; return $this; }

    public function getDeactivationDate(): ?DateTime { return $this->deactivationDate; }
    public function setDeactivationDate(?DateTime $deactivationDate): self { $this->deactivationDate = $deactivationDate; return $this; }

    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function setCreatedAt(DateTime $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getUpdatedAt(): ?DateTime { return $this->updatedAt; }
    public function setUpdatedAt(?DateTime $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }

    // ----------- Métodos para ChargeSpot ----------------
    /** @return ChargeSpot[] */
    public function getChargeSpot(): array
    {
        return $this->chargeSpot;
    }

    /** @param ChargeSpot[] $chargeSpot */
    public function setChargeSpot(array $chargeSpot): self
    {
        $this->chargeSpot = $chargeSpot;
        return $this;
    }

    public function addChargeSpot(ChargeSpot $chargeSpot): self
    {
        $this->chargeSpot[] = $chargeSpot;
        return $this;
    }
}
