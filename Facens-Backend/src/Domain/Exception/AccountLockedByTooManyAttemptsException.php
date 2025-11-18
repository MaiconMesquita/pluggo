<?php

namespace App\Domain\Exception;

use Throwable;

class AccountLockedByTooManyAttemptsException extends \Exception
{
    public function __construct($message = 'Your account has been locked due to too many failed password attempts.', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
