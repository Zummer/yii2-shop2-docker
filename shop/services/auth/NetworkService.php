<?php
namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;

class NetworkService
{
    private $users;
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
    public function auth($network, $identity): User
    {
        $user = $this->users->findByNetworkIdentity($network, $identity);
        if ($user) {
            return $user;
        }
        $user = User::signupByNetwork($network, $identity);
        $this->users->save($user);
        return $user;
    }
}