<?php

namespace App\Domain\Exception;

use Throwable;

class NotFoundException extends \Exception
{

    public function __construct($message = 'Object not found', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
