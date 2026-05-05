<?php

namespace App\Infra\Database\EntitiesOrm;

use App\Domain\Entity\Employee as EntityAdministrator;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Domain\Entity\ValueObject\EmployeeType;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employee')]
class Employee extends BaseOrm
{
    protected $pureClass = EntityAdministrator::class;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    public int $id;


    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    public string $name;

    #[ORM\Column(name: 'device_id', type: Types::STRING, length: 255, nullable: true)]
    public ?string $deviceId;

    #[ORM\Column(name: 'one_signal_id', type: Types::STRING, length: 255, nullable: true)]
    public ?string $oneSignalId;

    #[ORM\Column(type: Types::STRING, length: 11, nullable: true)]
    public ?string $cpf = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: true)]
    public ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    public ?string $phone = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $password = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    public bool $status = true;

    #[ORM\Column(name: "is_push_notification_enabled", type: Types::BOOLEAN, options: ['default' => true], nullable: true)]
    public ?bool $isPushNotificationEnabled = true;

    #[ORM\Column(name: 'password_attempt', type: Types::INTEGER, options: ['default' => 0],  length: 1, nullable: false)]
    public int $passwordAttempt = 0;

    #[ORM\Column(name: "change_password", type: Types::BOOLEAN, options: ['default' => false], nullable: true)]
    public bool $changePassword = false;

    #[ORM\Column(name: "deactivation_date", type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $deactivationDate = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    public DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE)]
    public ?DateTime $updatedAt = null;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $adjustedDate = DateTimeOffset::getAdjustedDateTime();

        $this->updatedAt = $adjustedDate;
        if (!isset($this->createdAt))
            $this->createdAt   = $adjustedDate;
    }

    /** @param EntityAdministrator $domain */
    public static function fromDomain($domain)
    {
        $base = parent::fromDomain($domain);

        return $base;
    }

    public function toDomain()
    {
        /** @var EntityAdministrator */
        $base = parent::toDomain();

        return $base;
    }
}
