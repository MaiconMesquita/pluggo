<?php

namespace App\Infra\Database\EntitiesOrm;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\DBAL\Types\Types;
use App\Domain\Entity\Host as EntityHost;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Infra\Database\EntitiesOrm\ChargeSpot;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'host')]
class Host extends BaseOrm
{
    protected $pureClass = EntityHost::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $name;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: false)]
    public string $email;

    #[ORM\Column(type: Types::STRING, length: 20, unique: true, nullable: false)]
    public string $phone;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $password;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $updatedAt = null;

    #[ORM\Column(name: 'deactivation_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $deactivationDate = null;

    // Relacionamento com ChargeSpot
    #[ORM\OneToMany(mappedBy: 'host', targetEntity: ChargeSpots::class, cascade: ['persist', 'remove'])]
    public Collection|PersistentCollection $chargeSpots;

    public function __construct()
    {
        $this->chargeSpots = new ArrayCollection();
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

    /** @param EntityHost $domain */
    public static function fromDomain($domain)
    {
        /** @var self $base */
        $base = parent::fromDomain($domain);

        // Associação dos ChargeSpots
        if (property_exists($domain, 'chargeSpot') && is_array($domain->getChargeSpot())) {
            foreach ($domain->getChargeSpot() as $spotDomain) {
                $spotOrm = ChargeSpots::fromDomain($spotDomain);
                $spotOrm->host = $base;
                $base->chargeSpots->add($spotOrm);
            }
        }

        return $base;
    }

    public function toDomain()
    {
        /** @var EntityHost $base */
        $base = parent::toDomain();

        // Mapeia ChargeSpots somente se estiverem carregados
        if ($this->chargeSpots instanceof PersistentCollection && $this->chargeSpots->isInitialized()) {
            foreach ($this->chargeSpots as $spot) {
                $base->addChargeSpot($spot->toDomain());
            }
        }

        return $base;
    }
}
