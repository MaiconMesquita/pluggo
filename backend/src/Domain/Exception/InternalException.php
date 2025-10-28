<?php

namespace App\Domain\Exception;

use Throwable;

class InternalException extends \Exception
{

    public function __construct($message = 'Internal error', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
