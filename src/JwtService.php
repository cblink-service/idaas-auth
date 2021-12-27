<?php

namespace Cblink\Service\IDaasAuth;

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Ramsey\Uuid\Uuid;

class JwtService
{
    protected $algo = 'AES-CBC-128';

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
            'iss' => 'cb-sign-center',
            'exp' => time() + $ttl,
            'iat' => time(),
            'nbf' => time(),
            'jti' => Uuid::uuid4()->toString(),
            'dat' => $this->encryptData($options, $secret)
        ];

        return JWT::encode($payload, base64_encode($appid.$secret));
    }

    /**
     * @param string $jwt
     * @param string $appid
     * @param string $secret
     * @return array
     */
    public function decode(string $jwt, string $appid = '', string $secret = '')
    {
        $data = JWT::decode($jwt, base64_encode($appid.$secret), ['HS256']);

        if (!property_exists($data, 'dat')) {
            throw new SignatureInvalidException('Signature verification failed');
        }

        return $this->decryptData($data->dat, $secret);
    }

    /**
     * 解密内容
     *
     * @param $jwt
     * @param string $secret
     * @return array
     */
    public function jsonData($jwt, string $secret = '')
    {
        $data = JWT::jsonDecode($jwt);

        if (!property_exists($data, 'dat')) {
            throw new SignatureInvalidException('Signature verification failed');
        }

        return $this->decryptData($data->dat, $secret);
    }

    /**
     * @param array $options
     * @param string $secret
     * @return false|string
     */
    protected function encryptData(array $options = [], string $secret = '')
    {
        return openssl_encrypt(serialize($options), $this->algo, $this->getPassphrase($secret));
    }

    /**
     * @param $dat
     * @param $secret
     * @return array
     */
    protected function decryptData($dat, $secret)
    {
        return unserialize(openssl_decrypt($dat, $this->algo, $this->getPassphrase($secret)));
    }

    /**
     * @param string $secret
     * @return false|string
     */
    protected function getPassphrase(string $secret = '')
    {
        return substr($secret, 0, 16);
    }
}
