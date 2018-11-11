<?php
namespace common\repositories;

use common\entities\User;

interface UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User;

    /**
     * @param string $token
     * @return User|null
     */
    public function getByPasswordResetToken(string $token): User;

    /**
     * @param string $token
     * @return bool
     */
    public function existsByPasswordResetToken(string $token): bool;

    /**
     * @param string $token
     * @return User
     */
    public function getByEmailConfirmToken(string $token): User;
}