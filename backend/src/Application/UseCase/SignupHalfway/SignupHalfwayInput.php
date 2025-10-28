<?php

namespace App\Application\UseCase\SignupHalfway;



class SignupHalfwayInput
{
    public string         $deviceId;
    public ?string        $name;
    public ?string        $selfiePhoto;
    public ?string        $documentFront;
    public ?string        $documentBack;
    public ?string        $street;
    public ?string        $postalCode;
    public ?string        $neighborhood;
    public ?string        $number;
    public ?string        $complement;
    public ?string        $city;
    public ?string        $rg;
    public ?string        $state;
    public ?string        $latitude;
    public ?string        $longitude;
    public ?string        $gender = null;
    public ?string        $maritalStatus = null;
    public ?string        $issuingState = null;
    public ?string        $issuingAuthority = null;
    public ?string        $fatherName = null;
    public ?string        $motherName = null;
    public ?string        $birthDate = null;
    public ?string        $nationality = null;
    public ?bool          $acceptedTermsOfUse;
    public ?bool          $acceptedCardTerms;
}
