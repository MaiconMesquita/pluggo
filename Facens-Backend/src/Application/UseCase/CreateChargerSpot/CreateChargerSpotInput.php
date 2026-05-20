<?php

namespace App\Application\UseCase\CreateChargerSpot;



class CreateChargerSpotInput
{
    public string        $name;
    public string        $latitude;
    public string        $longitude;
    public int           $hostId;
    public ?float        $pricePerKwh = null;
    public ?string $connectorType = null;
}
