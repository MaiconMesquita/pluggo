<?php

namespace App\Domain\Entity\ValueObject;

final class GenerateRandomPassword
{
    public function __construct(
        private string $passwordGenerated = "",
    ) {
        $this->generate();
    }

    public function generate()
    {
        $upperCaseLetters = 'ABCDEFGHJKLMNOPQRSTUVWXYZ';
        $lowerCaseLetters = 'abcdefghijkmnopqrstuvwxyz';
        $numbers = '0123456789';

        $password = [
            $upperCaseLetters[random_int(0, strlen($upperCaseLetters) - 1)],
            $lowerCaseLetters[random_int(0, strlen($lowerCaseLetters) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
        ];

        $allChars = $upperCaseLetters . $lowerCaseLetters . $upperCaseLetters . $lowerCaseLetters;
        for ($i = 4; $i < 10; $i++) {
            $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
        }

        shuffle($password);

        $this->passwordGenerated = implode('', $password);
    }

    public function getValue()
    {
        return $this->passwordGenerated;
    }
}