<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\ApiKey as EntityApiKey;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Infra\Database\CustomIdGenerators\UuidGenerator;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'api_key')]
class ApiKey extends BaseOrm
{
    protected $pureClass = EntityApiKey::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    public string $id;

    #[ORM\Column(type: Types::STRING)]
    public string $description;

    #[ORM\Column(type: Types::STRING)]
    public string $type;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE)]
    public ?DateTime $updatedAt = null;

    public function toDomain()
    {
        return new EntityApiKey(
            id: $this->id,
            description: $this->description,
            type: $this->type,
        );
    }

    /** @param EntityApiKey $domain */
    public static function fromDomain($domain)
    {
        $base = parent::fromDomain($domain);

        return $base;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $adjustedDate = DateTimeOffset::getAdjustedDateTime();

        $this->updatedAt = $adjustedDate;
        if (!isset($this->createdAt))
            $this->createdAt   = $adjustedDate;
    }
}
