<?php

namespace App\Domain\Exception;

use Throwable;

class InvalidDataException extends \Exception
{

    public function __construct($message = 'Payload is invalid', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
