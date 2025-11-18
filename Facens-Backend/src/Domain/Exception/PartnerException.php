<?php

namespace App\Domain\Exception;

use Throwable;

class PartnerException extends \Exception
{

    public function __construct(
        string $message,
        string $issuer,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $message . " - ISSUER - " . $issuer,
            1,
            $previous
        );
    }
}
