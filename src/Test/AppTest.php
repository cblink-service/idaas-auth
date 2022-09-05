<?php

namespace Cblink\Service\IDaasAuth\Test;

use Cblink\HyperfExt\Tools\Aes;

class AppTest
{
    protected $privateKey;

    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @param mixed $ttl
     * @throws \Throwable
     * @return string
     */
    public function getAccessToken($appid, $ttl = 7200)
    {
        $payload = [
            'appid' => $appid,
            'code' => 'testtest',
            'name' => '测试应用',
            'status' => 1,
            'type' => 2,
            'expire_time' => bcadd((string) time(), (string) $ttl),
        ];

        return Aes::encode($payload, $this->privateKey);
    }
}