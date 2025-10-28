<?php

namespace App\Application\UseCase\SignupProgress;

class SignupProgressOutput
{
    public string $fields;
    public int $step;
    public bool $changePassword;
    public string $userType;
}
