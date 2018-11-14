<?php
namespace shop\services\auth;

use shop\services\auth\AuthServiceInterface;
use shop\entities\User\User;
use shop\forms\auth\LoginForm;
use shop\repositories\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    private $users;
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }
    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Incorrect username or password..');
        }
        return $user;
    }
}
