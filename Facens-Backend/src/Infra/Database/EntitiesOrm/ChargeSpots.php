<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\ChargeSpots as EntityChargeSpots;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'charge_spots')]
class ChargeSpots extends BaseOrm
{
    protected $pureClass = EntityChargeSpots::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(name: "name", type: Types::STRING, nullable: false)]
    public string $name;

    #[ORM\Column(name: "latitude", type: Types::STRING, nullable: false)]
    public string $latitude;

    #[ORM\Column(name: "longitude", type: Types::STRING, nullable: false)]
    public string $longitude;

    #[ORM\Column(name: "price_per_kwh", type: Types::FLOAT, nullable: true)]
    public ?float $pricePerKwh = null;

    // ✅ RELACIONAMENTO COM REVIEWS
    #[ORM\OneToMany(
        mappedBy: 'spot',
        targetEntity: SpotReview::class,
        cascade: ['persist', 'remove']
    )]
    public Collection $reviews;

    #[ORM\Column(name: "connector_type", type: Types::STRING, nullable: true)]
    public ?string $connectorType = null;

    #[ORM\Column(name: "status", type: Types::STRING)]
    public string $status;

    #[ORM\ManyToOne(targetEntity: Host::class, inversedBy: 'charge_spots')]
    #[ORM\JoinColumn(name: 'host_id', referencedColumnName: 'id', nullable: false)]
    public Host $host;

    #[ORM\Column(name: "deactivation_date", type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $deactivationDate = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE, nullable: false)]
    public DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $updatedAt = null;

    // ✅ CONSTRUCTOR (IMPORTANTE)
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $adjustedDate = DateTimeOffset::getAdjustedDateTime();

        $this->updatedAt = $adjustedDate;

        if (!isset($this->createdAt)) {
            $this->createdAt = $adjustedDate;
        }
    }

    /** @param EntityChargeSpots $domain */
    public static function fromDomain($domain)
    {
        /** @var self $entity */
        $entity = parent::fromDomain($domain);

        return $entity;
    }

    public function toDomain(): EntityChargeSpots
    {
        /** @var EntityChargeSpots $base */
        $base = parent::toDomain();

        $base->setPricePerKwh($this->pricePerKwh);
        $base->setConnectorType($this->connectorType);

        // ✅ CONVERSÃO CORRETA DAS REVIEWS (ORM → DOMAIN)
        $reviews = [];
        if ($this->reviews) {
            foreach ($this->reviews as $review) {
                $domainReview = $review->toDomain();

                // 🔥 injeta o MESMO objeto (evita loop)
                $domainReview->setSpot($base);

                $reviews[] = $domainReview;
            }
        }
        $base->setReviews($reviews);

        $base->setHost($this->host->toDomain());
        $base->setLatitude($this->latitude);
        $base->setLongitude($this->longitude);
        $base->setStatus($this->status);
        $base->setDeactivationDate($this->deactivationDate);
        $base->setCreatedAt($this->createdAt);
        $base->setUpdatedAt($this->updatedAt);

        return $base;
    }
}
