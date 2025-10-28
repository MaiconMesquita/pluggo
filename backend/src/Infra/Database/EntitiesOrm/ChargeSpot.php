<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\ChargeSpot as ChargeSpotEntity;
use App\Infra\Database\EntitiesOrm\BaseOrm;
use App\Infra\Database\EntitiesOrm\Host;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: "charge_spots")]
class ChargeSpot extends BaseOrm
{
    protected $pureClass = ChargeSpotEntity::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $model;

    #[ORM\Column(type: "string", length: 100)]
    public string $latitude;

    #[ORM\Column(type: "string", length: 100)]
    public string $longitude;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    public ?float $pricePerKwh = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public array $reviews = [];

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    public ?string $connectorType = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: false)]
    public string $status = 'available';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $deactivationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Host::class, inversedBy: "chargeSpots")]
    #[ORM\JoinColumn(name: "host_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    public Host $host;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /** @param ChargeSpotEntity $domain */
    public static function fromDomain($domain)
    {
        /** @var self $base */
        $base = parent::fromDomain($domain);

        // Host será vinculado no momento da associação
        return $base;
    }

    public function toDomain()
    {
        /** @var ChargeSpotEntity $base */
        $base = parent::toDomain();

        // Host já vem do domínio
        $base->setHost($this->host->toDomain());

        return $base;
    }
}
