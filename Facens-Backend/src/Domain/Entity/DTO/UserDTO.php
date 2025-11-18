<?php
// DTO de resposta que inclui os campos dos dois objetos relacionados
namespace App\Domain\Entity\DTO;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $userType,
        public string $name,
        public string $cpf,
        public string $rg,
        public string $deviceId,
        public string $email,
        public string $phone,
        public ?string $street = null,
        public ?string $number = null,
        public ?string $complement = null,
        public ?string $neighborhood= null,
        public ?string $city= null,
        public ?string $state = null,
        public ?string $postalCode = null,
    ) {}
}
