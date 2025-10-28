<?php

namespace App\Application\UseCase\AcceptInvitationSms;

class AcceptInvitationSmsInput
{
    public bool $acceptInvitation;
    public int $smsId;
}
