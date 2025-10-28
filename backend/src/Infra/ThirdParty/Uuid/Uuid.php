<?php

namespace App\Infra\ThirdParty\Uuid;

interface Uuid
{
    public static function v4(): string;
}
