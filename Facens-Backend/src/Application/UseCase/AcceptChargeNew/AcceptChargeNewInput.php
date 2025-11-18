<?php

namespace App\Application\UseCase\AcceptChargeNew;

class AcceptChargeNewInput
{
    public bool $acceptTransaction;
    public ?bool $isPackagePlan;
    public int $installmentCount;
    public string $establishmentId;
    public string $description;
    public float $amount;
    public string $signature;
    public string $uniqueId;
    public string $deviceId;
}
