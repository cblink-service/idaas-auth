<?php

namespace Cblink\Service\IDaasAuth;

use Hyperf\Utils\Arr;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;

class User implements AuthenticatableInterface
{
    /**
     * @var
     */
    public $userId;

    /**
     * @var
     */
    public $info;

    public function __construct($user)
    {
        $this->userId = $user->user_id;
        $this->info = $user->user;
    }

    /**
     * 获取UserId
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
     * 获取openid
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getOpenid()
    {
        return Arr::get($this->info, 'openid');
    }

    /**
     * 获取用户头像
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getAvatar()
    {
        return Arr::get($this->info, 'avatar', '');
    }

    /**
     * 获取用户名
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getNickname()
    {
        return Arr::get($this->info, 'nickname', '');
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    /**
     * 获取用户ID
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getId();
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