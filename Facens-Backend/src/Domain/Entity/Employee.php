<?php

namespace App\Domain\Entity;
date_default_timezone_set('America/Sao_Paulo');

use App\Domain\Entity\ValueObject\EmployeeType;
use DateTime;

final class Employee
{
    private ?int $id = null;
    private ?string $cpf = null;
    private string $name;
    private string $email;
    private string $phone;
    private string $password;
    private bool $status;
    private int $passwordAttempt;
    private ?string $deviceId = null;
    private ?string $oneSignalId = null;
    private bool $changePassword;
    private DateTime $createdAt;
    private ?DateTime $deactivationDate = null;
    private ?DateTime $updatedAt = null;
    private ?bool $isPushNotificationEnabled = true;

    public static function create(
        string $name,
        string $email,
        string $phone,
        string $password,
       
        ?string $cpf = null,
       
    ): self {
        $instance = new self();
        $instance->name = $name;
        $instance->email = $email;
        $instance->phone = $phone;
        $instance->password = $password;
        if (!empty($cpf)) $instance->cpf = $cpf;

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
            'cpf' => $this->cpf,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'oneSignalId' => $this?->oneSignalId,
            'deviceId' => $this?->deviceId,
           'isPushNotificationEnabled' => $this->isPushNotificationEnabled,
            'changePassword' => $this->changePassword,
            'passwordAttempt' => $this->passwordAttempt,
            'deactivationDate' => $this->deactivationDate ? $this->deactivationDate->format('Y-m-d H:i:s') : null,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf)
    {
        $this->cpf = $cpf;
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

    public function getPasswordAttempt(): ?int
    {
        return $this->passwordAttempt;
    }

    public function setPasswordAttempt(?int $passwordAttempt): self
    {
        $this->passwordAttempt = $passwordAttempt;
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

    public function getIsPushNotificationEnabled(): ?bool
    {
        return $this->isPushNotificationEnabled;
    }

    public function setIsPushNotificationEnabled(?bool $isPushNotificationEnabled): self
    {
        $this->isPushNotificationEnabled = $isPushNotificationEnabled;
        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getChangePassword(): ?bool
    {
        return $this->changePassword;
    }

    public function setChangePassword(?bool $changePassword): self
    {
        $this->changePassword = $changePassword;
        return $this;
    }

    public function getDeactivationDate(): ?DateTime
    {
        return $this->deactivationDate;
    }

    public function setDeactivationDate(?DateTime $deactivationDate): self
    {
        $this->deactivationDate = $deactivationDate;
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
