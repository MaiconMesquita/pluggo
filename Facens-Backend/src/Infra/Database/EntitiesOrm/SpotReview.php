<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\SpotReview as DomainSpotReview;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'spot_reviews')]
#[ORM\UniqueConstraint(name: 'unique_driver_spot', columns: ['driver_id', 'spot_id'])]
class SpotReview extends BaseOrm
{
    protected $pureClass = DomainSpotReview::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\ManyToOne(targetEntity: ChargeSpots::class)]
    #[ORM\JoinColumn(name: 'spot_id', referencedColumnName: 'id', nullable: false)]
    public ChargeSpots $spot;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    #[ORM\JoinColumn(name: 'driver_id', referencedColumnName: 'id', nullable: false)]
    public Driver $driver;

    #[ORM\Column(type: Types::INTEGER)]
    public int $rating;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $comment = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $updatedAt = null;

    // 🔥 timestamps automáticos igual Employee
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

    /** @param DomainSpotReview $domain */
    public static function fromDomain($domain)
    {
        $base = parent::fromDomain($domain);

        return $base;
    }

    public function toDomain(): DomainSpotReview
    {
        /** @var DomainSpotReview $base */
        $base = parent::toDomain();

        $base->setRating($this->rating);
        $base->setComment($this->comment);

        // // 🔥 ESSENCIAL
        // $base->setSpot($this->spot->toDomain());

        // se tiver driver no domain também
        $base->setDriver($this->driver->toDomain());

        $base->setCreatedAt($this->createdAt);

        return $base;
    }
}
