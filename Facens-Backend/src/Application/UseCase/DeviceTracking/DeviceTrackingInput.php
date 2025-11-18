<?php

namespace App\Application\UseCase\DeviceTracking;



class DeviceTrackingInput
{
    public string     $deviceId; //deviceId novo que vai ser usado
    public string     $entity; //qual repositório usar
    public ?string     $email = null; //envio do token
    public ?string $document = null;
    public ?string     $oneSignalId = null;
    public string $deviceModel;
    public string $deviceType;
    public string $os;
}
