<?php

namespace App\Application\UseCase\ChangeProfile;

use App\Domain\Exception\InvalidDataException;

class ChangeProfileInput
{
    public ?bool $isUser = null;

    // Campos comuns
    public ?int $id = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $cpf = null;
    public ?float $currentBalance = null;
    public ?bool $status = null;
    public ?string $password = null;

    // Campos exclusivos de Usuário
    public ?string $rg = null;
    public ?string $deviceId = null;
    public ?\DateTime $birthDate = null;
    public ?string $gender = null;
    public ?string $fatherName = null;
    public ?string $motherName = null;
    public ?bool $CCBStatus = null;
    public ?string $street = null;
    public ?string $number = null;
    public ?string $complement = null;
    public ?string $neighborhood = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $postalCode = null;
    public ?bool $codeValidation = null;
    public ?bool $isPushNotificationEnabled = null;
    public ?bool $isPromotionalNotificationEnabled = null;

    public ?string $selfiePhoto = null;
    public ?string $documentFront = null;
    public ?string $documentBack = null;

    // Campos exclusivos de Funcionário
    public ?int $establishmentId = null;
    public ?float $amountToReceive = null;

    public function validate(): void
    {
        // Validação para usuários
        if ($this->isUser === true) {
            if ($this->establishmentId !== null || $this->amountToReceive !== null) {
                throw new InvalidDataException('Fields exclusive to employees cannot be filled for users.');
            }
        }

        // Validação para funcionários
        if ($this->isUser === false) {
            if (
                $this->rg !== null ||
                $this->deviceId !== null ||
                $this->birthDate !== null ||
                $this->gender !== null ||
                $this->fatherName !== null ||
                $this->motherName !== null ||
                $this->CCBStatus !== null ||
                $this->street !== null ||
                $this->number !== null ||
                $this->complement !== null ||
                $this->neighborhood !== null ||
                $this->city !== null ||
                $this->state !== null ||
                $this->postalCode !== null
            ) {
                throw new InvalidDataException('Fields exclusive to users cannot be filled for employees.');
            }
        }
    }

}
