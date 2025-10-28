<?php

namespace App\Infra\Database\EntitiesOrm;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Domain\Entity\CarModel as EntityCarModel;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'car_model')]
class CarModel extends BaseOrm
{
    protected $pureClass = EntityCarModel::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $model;

    #[ORM\Column(name: 'driver_id', type: Types::INTEGER, nullable: false)]
    public int $driverId;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $updatedAt = null;

    // Relacionamento com Driver
    #[ORM\ManyToOne(targetEntity: Driver::class, inversedBy: "carModels")]
    #[ORM\JoinColumn(name: "driver_id", referencedColumnName: "id", onDelete: "CASCADE")]
    public Driver $driver;

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

    /** @param EntityCarModel $domain */
    public static function fromDomain($domain)
    {
        /** @var self $base */
        $base = parent::fromDomain($domain);

        // O driver será vinculado no momento da associação (Driver::fromDomain)
        return $base;
    }

    public function toDomain()
    {
        /** @var EntityCarModel $base */
        $base = parent::toDomain();

        $base->setDriverId($this->driverId);

        return $base;
    }
}
