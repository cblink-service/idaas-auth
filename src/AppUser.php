<?php

namespace Cblink\Service\IDaasAuth;

use Hyperf\Utils\Arr;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;

class AppUser implements AuthenticatableInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return Arr::get($this->data, 'type') == 1;
    }

    /**
     * @return string
     */
    public function name() :string
    {
        return Arr::get($this->data, 'name', '');
    }

    /**
     * @return string
     */
    public function code()
    {
        return Arr::get($this->data, 'code');
    }

    /**
     * @return string
     */
    public function status()
    {
        return Arr::get($this->data, 'status');
    }

    /**
     * @return int|string
     */
    public function expireTime()
    {
        return Arr::get($this->data, 'expire_time', 0);
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'appid';
    }

    /**
     * @return array|\ArrayAccess|mixed
     */
    public function getAuthIdentifier()
    {
        return Arr::get($this->data, $this->getAuthIdentifierName());
    }

    public function getAuthPassword(): ?string
    {
        return null;
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken(string $value)
    {
        return null;
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }
}
