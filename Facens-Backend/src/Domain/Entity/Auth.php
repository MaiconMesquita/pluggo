<?php

namespace App\Domain\Entity;

use Exception;

final class Auth
{
    public function __construct(
        private array $scopes,
        private string $timezone,
        private string $authType, // 'user', 'employee', 'establishment', 'supplier'...
        private ?string $userType = null,
        private ?string $employeeType = null,
        private ?string $establishmentType = null,
        private ?string $supplierType = null,
        private ?string $driverId = null,
        private ?string $hostId = null,
    ) {}

    // -------- GETTERS --------
    public function getDriver(): ?string         { return $this->driverId; }
    public function getHost(): ?string     { return $this->hostId; }

    public function getUserType(): ?string         { return $this->userType; }
    public function getEmployeeType(): ?string     { return $this->employeeType; }
    public function getEstablishmentType(): ?string{ return $this->establishmentType; }
    public function getSupplierType(): ?string     { return $this->supplierType; }

    public function getTimezone(): string      { return $this->timezone; }
    public function getAuthType(): string      { return $this->authType; }

    // -------- PERMISSÕES --------
    public function checkScope(string $scope): bool
    {
        return in_array($scope, $this->scopes, true);
    }

    // -------- LOGIN / GET / CHECK --------
    public function login(): void
    {
        $GLOBALS['authenticated'] = $this;
    }

    public static function getLogged(): Auth
    {
        if (empty($GLOBALS['authenticated'])) {
            throw new Exception('No authenticated entity found');
        }
        return $GLOBALS['authenticated'];
    }

    public static function hasLogged(): bool
    {
        return !empty($GLOBALS['authenticated']);
    }

    public function __destruct()
    {
        // unset($GLOBALS['authenticated']);
    }
}
