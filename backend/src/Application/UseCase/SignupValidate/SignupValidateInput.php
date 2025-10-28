<?php

namespace App\Application\UseCase\SignupValidate;



class SignupValidateInput
{
    public ?string        $password;
    public ?string        $cpf;
    public ?string        $email;
    public ?bool        $acceptedTermsOfUse;
    public ?bool        $acceptedAccreditationTerms;
    public ?string $latitude = null;
    public ?string $longitude = null;
    public ?string     $oneSignalId = null;
    public bool        $isUser;
    public string        $deviceId;
}
