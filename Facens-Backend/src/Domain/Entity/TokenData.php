<?php

namespace App\Domain\Entity;

class TokenData
{
    public function __construct(
        public string $tid,
        public string $iss,
        public string $aud,
        public string $iat,
        public string $nfb,
        public int $exp,
        public array $data,
    ) {
    }
}
