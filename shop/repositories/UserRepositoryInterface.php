<?php
namespace shop\repositories;

use shop\entities\User\User;

interface UserRepositoryInterface
{
    /**
     * @param \shop\entities\User\User $user
     */
    public function save(User $user): void;

    /**
     * @param string $email
     * @return \shop\entities\User\User
     */
    public function getByEmail(string $email): User;

    /**
     * @param string $token
     * @return \shop\entities\User\User|null
     */
    public function getByPasswordResetToken(string $token): User;

    /**
     * @param string $token
     * @return bool
     */
    public function existsByPasswordResetToken(string $token): bool;

    /**
     * @param string $token
     * @return \shop\entities\User\User
     */
    public function getByEmailConfirmToken(string $token): User;

    /**
     * @param string $value
     * @return User|null
     */
    public function findByUsernameOrEmail(string $value): ?User;
}