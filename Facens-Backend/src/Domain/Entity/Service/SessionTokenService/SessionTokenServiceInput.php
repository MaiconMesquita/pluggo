<?php

namespace App\Domain\Entity\Service\SessionTokenService;

use App\Domain\Entity\{Driver, Host, Employee};

class SessionTokenServiceInput
{
    public ?Driver $driver = null;
    public ?Host $host = null;
    public ?Employee $employee = null;
    public ?bool $leadValidated = true;
    public string $password;


    public function __construct(
        string $password,
        ?Driver $driver = null,
        ?Host $host = null,
        ?Employee $employee = null,
        ?bool $leadValidated = true,
    ) {
        $this->password = $password;
        $this->driver = $driver;
        $this->host = $host;
        $this->employee = $employee;
        $this->leadValidated = $leadValidated;
    }
}
