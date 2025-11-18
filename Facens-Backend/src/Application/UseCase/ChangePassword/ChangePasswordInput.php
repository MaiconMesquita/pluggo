<?php

namespace App\Application\UseCase\ChangePassword;

class ChangePasswordInput
{
    public string $currentPassword;
    public string $newPassword;
}
