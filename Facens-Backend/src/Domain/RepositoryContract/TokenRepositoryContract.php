<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\Token;

interface TokenRepositoryContract
{
    public function create(
        Token $token,
    ): Token;

    public function getAndDeleteToken(
        string $tokenId
    ): bool;

    public function revokeTokenByUserId(
        int $userId,
        bool $realUser
    ): void;

}
