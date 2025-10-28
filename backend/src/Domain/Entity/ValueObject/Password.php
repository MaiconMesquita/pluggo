<?php

namespace App\Domain\Entity\ValueObject;

use App\Domain\Exception\InvalidDataException;

final class Password
{

    public function __construct(
        private string $rawPassword,
        bool $verifyIfIStrong = True
    ) {
        if ($verifyIfIStrong)
            $this->passwordIsStrong();
    }

    public function passwordIsStrong(): void
    {

        $password = $this->rawPassword;
        $number = preg_match('@[0-9]@', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        // $specialChars = preg_match('@[^\w]@', $password);

        if (
            strlen($password) < 8 || !$number || !$uppercase || !$lowercase //|| !$specialChars
        )
            throw new InvalidDataException(
                "Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter."
            );
    }

    public function getValue(): string
    {
        return $this->rawPassword;
    }

    public function getPasswordHash(): string
    {
        return password_hash($this->rawPassword, PASSWORD_DEFAULT);
    }
}