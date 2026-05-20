<?php

namespace App\Application\UseCase\UpdateHost;

class UpdateHostInput
{
    public int $id;
    public ?string $name = null;
    public ?string $phone = null;
    public ?string $email = null;
}
