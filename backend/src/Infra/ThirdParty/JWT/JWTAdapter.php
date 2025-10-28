<?php

namespace App\Infra\ThirdParty\JWT;


use App\Domain\Entity\TokenData;
use App\Domain\Entity\DTO\TokenDTO;
use App\Infra\ThirdParty\Uuid\Uuid;
use App\Infra\ThirdParty\JWT\JWT as JWTContract;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAdapter implements JWTContract
{

    private JWT $jwt;

    public function __construct(private Uuid $uuid)
    {
        $this->jwt = new JWT();
    }



    public function encode(array $payload, ?int $expiresInSeconds = null, ?string $key = null): TokenDTO
    {
        $secret = $key ?? $_ENV['JWT_KEY'];

        if (empty($payload['tid'])) $tokenId = $this->uuid::v4();
        else $tokenId = $payload['tid'];

        $tokenExpirationTime = null;
        if ($expiresInSeconds)
            $tokenExpirationTime = (int)time() + $expiresInSeconds;

        $payload['tid'] = $tokenId;
        $payload['iss'] = $_ENV['APP_URL'];
        $payload['aud'] = $_ENV['APP_URL'];
        $payload['iat'] = (int)time();
        $payload['nfb'] = (int)time();

        if ($tokenExpirationTime)
            $payload['exp'] = $tokenExpirationTime;

        $jwt = $this->jwt::encode($payload, $secret, 'HS256');

        $token = new TokenDTO($jwt, $tokenExpirationTime);
        $token->id = $tokenId;
        return $token;
    }

    public function decode(string $rawToken, ?string $key = null): TokenData
    {
        $decoded = (array) $this->jwt::decode($rawToken, new Key($key ?? $_ENV['JWT_KEY'], 'HS256'));

        return new TokenData(
            $decoded['tid'],
            $decoded['iss'],
            $decoded['aud'],
            $decoded['iat'],
            $decoded['nfb'],
            $decoded['exp'],
            $decoded,
        );
    }
}
