<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\MessageHistory as EntityMessageHistory;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'message_history')]
class MessageHistory extends BaseOrm
{
    protected $pureClass = EntityMessageHistory::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;
    
    #[ORM\Column(type: Types::STRING, nullable: false)]
    public string $entityType;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    public string $message;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    public int $entityId;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, nullable: false)] // Alteração aqui
    public DateTime $createdAt;


    /** @param EntitySmsHistory $domain */
    public static function fromDomain($domain)
    {
        $base = parent::fromDomain($domain);

        return $base;
    }

    public function toDomain()
    {
        /** @var EntityAuditLog */
        $base = parent::toDomain();

        return $base;
    }
}
