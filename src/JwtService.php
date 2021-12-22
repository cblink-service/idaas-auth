<?php

namespace Cblink\Service\IDaasAuth;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

class JwtService
{
    /**
     * @param array $options
     * @param string $appid
     * @param string $secret
     * @param int $ttl
     * @return string
     */
    public function encode(array $options = [], string $appid = '', string $secret = '', int $ttl = 7200): string
    {
        $payload = [
            'iss' => config('app_name'),
            'exp' => time() + $ttl,
            'iat' => time(),
            'nbf' => time(),
            'jti' => Uuid::uuid4()->toString(),
            'dat' => serialize($options),
        ];

        return  JWT::encode($payload, base64_encode($appid.$secret));
    }

    /**
     * @param string $jwt
     * @param string $appid
     * @param string $secret
     * @return object
     */
    public function decode(string $jwt, string $appid = '', string $secret = '')
    {
        return JWT::decode($jwt, base64_encode($appid.$secret), ['HS256']);
    }
}
