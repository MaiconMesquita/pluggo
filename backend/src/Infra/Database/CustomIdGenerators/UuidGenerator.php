<?php

namespace App\Infra\Database\CustomIdGenerators;

use App\Infra\ThirdParty\Uuid\RamseyUuidAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;


class UuidGenerator extends AbstractIdGenerator
{
    public function generateId(EntityManagerInterface $em, $entity)
    {
        return RamseyUuidAdapter::v4();
    }
}
