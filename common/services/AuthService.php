<?php
namespace common\services;

use common\entities\User;
use common\forms\LoginForm;
use common\repositories\UserRepositoryInterface;

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
