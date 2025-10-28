<?php

namespace App\Domain\Entity;

final class Address
{
    private string $id;
    public function __construct(
        private string $streetName,
        private string $streetNumber,
        private ?string $complement,
        private string $neighborhood,
        private string $zipcode,
        private string $city,
        private string $uf,
        private bool $main
    ) {
        $this->zipcode = str_replace('-', '', $this->zipcode);
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function getMain(): bool
    {
        return $this->main;
    }

    public function setMain(bool $main)
    {
        $this->main = $main;
    }
}
