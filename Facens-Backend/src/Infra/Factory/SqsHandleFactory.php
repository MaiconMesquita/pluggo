<?php

namespace App\Infra\Factory;

use App\Application\Handler\Handler;

class SqsHandleFactory
{
    public static function getHandler(string $eventName, $body): ?Handler
    {
        return null;
    }
}
