<?php

namespace App\Application\UseCase\UpdateDriver;

class UpdateDriverInput
{
    public int $id;
    public ?string $name = null;
    public ?string $phone = null;
    public ?string $email = null;
}