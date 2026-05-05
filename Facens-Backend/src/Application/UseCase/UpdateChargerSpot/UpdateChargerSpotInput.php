<?php

namespace App\Application\UseCase\UpdateChargerSpot;



class UpdateChargerSpotInput
{
    public int $id;
    public ?string        $name;
    public ?string        $latitude;
    public ?string        $longitude;
    public ?float        $pricePerKwh = null;
    public ?string $connectorType = null;
}
