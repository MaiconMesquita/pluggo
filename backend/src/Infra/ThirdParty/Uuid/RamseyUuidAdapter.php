<?php

namespace App\Infra\ThirdParty\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class RamseyUuidAdapter implements Uuid
{

    public static function v4(): string
    {
        $uuid = RamseyUuid::uuid4();
        return $uuid;
    }
}
