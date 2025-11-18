<?php

namespace App\Infra\ThirdParty\Mail;

interface Mail
{
    public function sendSampleMail(
        array $to,
        string $subject,
        string $message,
        bool $isHTML = false
    ): bool;
}
