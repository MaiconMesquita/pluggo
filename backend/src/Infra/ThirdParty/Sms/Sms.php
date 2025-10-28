<?php

namespace App\Infra\ThirdParty\Sms;

use App\Domain\Entity\ValueObject\{PhoneNumber, SmsType};


interface Sms
{
    public function sendMessage(string $message, PhoneNumber $phoneNumber);
}
