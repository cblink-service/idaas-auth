<?php

namespace Cblink\Service\IDaasAuth;

use Cblink\HyperfExt\Tools\Aes;
use Hyperf\HttpServer\Contract\RequestInterface;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;
use HyperfExt\Auth\Contracts\GuardInterface;
use HyperfExt\Auth\GuardHelpers;

abstract class AppGuard implements GuardInterface
{
    use GuardHelpers;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function user(): ?AuthenticatableInterface
    {
        $token = $this->request->header('authorization', $this->request->header('Authorization'));

        if (! $this->user && ! empty($token) && preg_match('/^Bearer\s[\w\-\/+=]+$/', $token)) {
            $accessToken = trim(substr($token, 6));

            $data = Aes::decode($accessToken, $this->getPublicKey(), true);

            if ($data) {
                $this->user = new User($data);
            }
        }

        return $this->user;
    }

    /**
     * @return string
     */
    abstract public function getPublicKey() :string;


    public function validate(array $credentials = []): bool
    {
        return true;
    }
}