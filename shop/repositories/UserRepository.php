<?php
namespace shop\repositories;

use shop\repositories\NotFoundException;
use shop\repositories\UserRepositoryInterface;
use shop\entities\User\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        if(!$user->delete()) {
            throw new \RuntimeException('Deleting error.');
        }
    }

    /**
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User
    {
        $user = $user = $this->getByConditions(['email' => $email]);
        return $user;
    }

    /**
     * @param string $token
     * @return User
     */
    public function getByPasswordResetToken(string $token): User
    {
        $user = $this->getByConditions(['password_reset_token' => $token]);
        return $user;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool)User::findByPasswordResetToken($token);
    }

    /**
     * @param string $token
     * @return User
     */
    public function getByEmailConfirmToken(string $token): User
    {
        $user = $this->getByConditions(['email_confirm_token' => $token]);
        return $user;
    }

    /**
     * @param array $conditions
     * @return \shop\entities\User\User
     */
    private function getByConditions(array $conditions): User
    {
        $user = User::find()->andWhere($conditions)->limit(1)->one();
        if (!$user) {
            throw new NotFoundException('User is not found.');
        }
        return $user;
    }

    /**
     * @param string $value
     * @return \shop\entities\User\User|null
     */
    public function findByUsernameOrEmail(string $value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    /**
     * @param $id
     * @return User
     */
    public function get($id): User
    {
        return $this->getByConditions(['id' => $id]);
    }

    public function find($id): ?User
    {
        return User::findOne($id);
    }

    public function findActiveById($id): ?User
    {
        return User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
    }
}

