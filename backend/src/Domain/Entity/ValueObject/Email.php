<?php

namespace App\Domain\Entity\ValueObject;

use DomainException;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException('The email is not valid');
        }
        $this->email = strtolower($email);
    }

    public function getValue()
    {
        return $this->email;
    }
}