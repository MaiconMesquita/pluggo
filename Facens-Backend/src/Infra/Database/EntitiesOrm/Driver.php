<?php

namespace App\Infra\Database\EntitiesOrm;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\DBAL\Types\Types;
use App\Domain\Entity\Driver as EntityDriver;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'driver')]
class Driver extends BaseOrm
{
    protected $pureClass = EntityDriver::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $name;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: true)]
    public ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    public ?string $phone = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $latitude = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $longitude = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $password = null;

    #[ORM\Column(name: 'one_signal_id', type: Types::STRING, length: 255, nullable: true)]
    public ?string $oneSignalId = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $updatedAt = null;

    // --- Relacionamento com CarModel ---
    #[ORM\OneToMany(mappedBy: "driver", targetEntity: CarModel::class, cascade: ["persist", "remove"])]
    public Collection|PersistentCollection $carModels;

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

    public function __construct()
    {
        $this->carModels = new ArrayCollection();
    }

    /** @param EntityDriver $domain */
    public static function fromDomain($domain)
    {
        /** @var self $base */
        $base = parent::fromDomain($domain);

        // Mapeia os CarModels do domínio para ORM
        if (method_exists($domain, 'getCarModel')) {
            foreach ($domain->getCarModel() as $carModelDomain) {
                $carModelOrm = CarModel::fromDomain($carModelDomain);
                $carModelOrm->driver = $base;
                $base->carModels->add($carModelOrm);
            }
        }

        return $base;
    }

    public function toDomain()
    {
        /** @var EntityDriver $base */
        $base = parent::toDomain();

        // Mapeia os CarModels ORM para domínio
        if ($this->carModels instanceof PersistentCollection && $this->carModels->isInitialized()) {
            foreach ($this->carModels as $carModel) {
                $base->addCardModel($carModel->toDomain());
            }
        }

        return $base;
    }
}
