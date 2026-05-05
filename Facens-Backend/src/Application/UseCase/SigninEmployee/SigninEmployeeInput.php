<?php

namespace App\Application\UseCase\SigninEmployee;

class SigninEmployeeInput
{
    public string $email;
    public string $password;
    public ?string $deviceId = null;
    public ?string $oneSignalId = null;
}
