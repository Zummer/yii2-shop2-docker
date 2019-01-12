<?php
namespace shop\repositories;

use shop\entities\User\User;

class MemoryUserRepository implements UserRepositoryInterface
{
    private $items = [];

    public function save(User $user): void
    {
        $this->items[] = $user;
    }

    public function getByEmail(string $email): User
    {
        foreach ($this->items as $item) {
            if ($item->email == $email) {
                return $item;
            }
        }

        return null;
    }

    public function getByPasswordResetToken(string $token): User
    {
        // TODO: Implement getByPasswordResetToken() method.
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        // TODO: Implement existsByPasswordResetToken() method.
    }

    public function getByEmailConfirmToken(string $token): User
    {
        // TODO: Implement getByEmailConfirmToken() method.
    }

    public function findByUsernameOrEmail(string $value): ?User
    {
        // TODO: Implement findByUsernameOrEmail() method.
    }

    public function get($id): User
    {
        // TODO: Implement get() method.
    }

    public function find($id): ?User
    {
        // TODO: Implement find() method.
    }

    public function findActiveById($id): ?User
    {
        // TODO: Implement findActiveById() method.
    }
}