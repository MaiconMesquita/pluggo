<?php

namespace App\Application\UseCase\AcceptAndVerifyCodeGeneral;



class AcceptAndVerifyCodeGeneralInput
{
    public string        $code;
    public string        $type;
    public string        $entity;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $deviceId = null;
    public string $channel;
}
