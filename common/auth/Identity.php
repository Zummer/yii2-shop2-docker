<?php

namespace common\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class Identity implements IdentityInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function findIdentity($id)
    {
        $user = self::getRepository()->findActiveById($id);
        return $user ? new self($user): null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getUsername(): string
    {
        return $this->user->username;
    }

    public function getAuthKey(): string
    {
        return $this->user->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    private static function getRepository(): UserRepository
    {
        return \Yii::$container->get(UserRepository::class);
    }
}
