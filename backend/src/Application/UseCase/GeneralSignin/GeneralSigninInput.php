<?php

namespace App\Application\UseCase\GeneralSignin;

class GeneralSigninInput
{
    public string $password;
    public string $email;
    public string $entityType; 
}
