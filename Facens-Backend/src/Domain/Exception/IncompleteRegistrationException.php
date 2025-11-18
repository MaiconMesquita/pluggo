<?php

namespace App\Domain\Exception;

use Throwable;

class IncompleteRegistrationException extends \Exception
{
    public function __construct($message = 'Registration is incomplete. Please check the required fields.', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
