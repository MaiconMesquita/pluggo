<?php
namespace App\Domain\Entity\DTO;

final class TokenDTO
{
    public string $id; 
    public function __construct(
        public string $token,
        public ?int $expirationTime = null
    ) {
    }
}