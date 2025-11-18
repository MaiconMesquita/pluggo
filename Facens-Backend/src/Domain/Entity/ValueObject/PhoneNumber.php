<?php

namespace App\Domain\Entity\ValueObject;

use DomainException;

final class PhoneNumber
{

    private $ddi;
    private $ddd;
    private $phoneNumber;

    public function __construct(string $phone)
    {
        $this->validate($phone);
    }

    private function validate($phone)
    {
        $phoneString = preg_replace('/\D/', '', $phone);
        if (preg_match('/^(?:(?:\+|00)?(55))?(\d{2})?(\d{8,9})$/', $phoneString, $matches) === false) {
            throw new DomainException("The phone number is not valid");
        }

        if (strlen($phoneString) <= 15 && strlen($phoneString) >= 12) {
            $ddi = $matches[1] ?? '';
            $ddd =  $matches[2] ?? '';
            $number = $matches[3] ?? '';
        } else {
            if (strlen($phoneString) >= 10) {
                $ddi = '';
                $ddd = substr($phoneString, 0, 2);
                $number = substr($phoneString, 2);
            } else {
                throw new DomainException("The phone number is not valid");
            }
        }

        if (!$ddd || !$number) throw new DomainException("The phone number is not valid ");

        $this->ddi = $ddi;
        $this->ddd = $ddd;
        $this->phoneNumber = $number;
    }

    public function getNumber()
    {
        return $this->phoneNumber;
    }

    public function getDDD()
    {
        return $this->ddd;
    }

    public function getDDI()
    {
        return $this->ddi;
    }

    public function getFullPhoneNumber()
    {
        return $this->ddi . $this->ddd . $this->phoneNumber;
    }
}