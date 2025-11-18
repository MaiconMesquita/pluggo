<?php

namespace App\Domain\Exception;

use Throwable;

class NotAcceptableException extends \Exception
{

    public function __construct($message = 'Action not permited', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
