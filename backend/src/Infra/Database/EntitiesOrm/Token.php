<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\{
    Token as EntityToken
};
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity]
#[ORM\Table(name: 'token')]
class Token extends BaseOrm
{
    protected $pureClass = EntityToken::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::STRING)]
    public string $id;

    #[ORM\Column(name: 'driver_id', type: Types::INTEGER, nullable: true)]
    public int $driverId;

    #[ORM\Column(name: 'host_id', type: Types::INTEGER, nullable: true)]
    public int $hostId;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $code;

    #[ORM\Column(type: Types::STRING)]
    public string $type;
}
