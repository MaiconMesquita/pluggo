<?php

namespace App\Domain\Exception;

use Throwable;

class DeviceNotFoundException extends \Exception
{
    public function __construct($message = 'Device not found', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
