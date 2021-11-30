<?php

namespace Cblink\Service\IDaasAuth;

use Hyperf\HttpServer\Contract\RequestInterface;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;
use HyperfExt\Auth\Contracts\GuardInterface;
use HyperfExt\Auth\GuardHelpers;

abstract class Guard implements GuardInterface
{
    use GuardHelpers;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var JwtService
     */
    protected $service;

    public function __construct(RequestInterface $request, JwtService $service)
    {
        $this->request = $request;
        $this->service = $service;
    }

    public function user(): ?AuthenticatableInterface
    {
        $token = $this->request->header('authorization',  $this->request->header('Authorization'));

        if (!$this->user && preg_match("/^Bearer\s[\w-]+.[\w-]+.[\w-]+$/", $token)) {

            $accessToken = str_replace("Bearer ", "", $token);

            try {
                $jwt = $this->service->decode($accessToken, $this->getAppId(), $this->getAppSecret());
            } catch (\Exception $exception) {
                return null;
            }

            if (isset($jwt->user_id)) {
                $this->user = new User($jwt);
            }
        }

        return $this->user;
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        return true;
    }

    /**
     * @return string
     */
    abstract public function getAppId();

    /**
     * @return string
     */
    abstract public function getAppSecret();
}