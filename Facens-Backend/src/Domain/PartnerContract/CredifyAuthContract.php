<?php

namespace App\Domain\PartnerContract;

use App\Domain\Entity\CredifySessionPayload;
use App\Domain\Entity\CredifySession;

interface CredifyAuthContract
{
    public function session(CredifySessionPayload $credifySessionPayload): CredifySession;
}
