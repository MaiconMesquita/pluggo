<?php

namespace App\Domain\Exception;

use Throwable;

class InvalidAuthException extends \Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The username or password was wrong', 1, $previous);
    }
}