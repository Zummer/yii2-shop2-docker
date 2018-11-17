<?php
namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepositoryInterface;

class NetworkService implements NetworkServiceInterface
{
    private $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * @param $network
     * @param $identity
     * @param $email
     * @return User
     */
    public function auth($network, $identity, $email): User
    {
        // найти пользователя с такой сетью
        $user = $this->users->findByNetworkIdentity($network, $identity);
        if ($user) {
            return $user;
        }

        // найти пользователя с таким email
        $user = $this->users->findByUsernameOrEmail($email);
        if ($user) {
            $this->attach($user->id, $network, $identity);
            return $user;
        }

        // зарегистрировать пользователя
        $user = User::signupByNetwork($network, $identity, $email);
        $this->users->save($user);
        return $user;
    }

    /**
     * @param $id
     * @param $network
     * @param $identity
     */
    public function attach($id, $network, $identity): void
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Network is already signed up.');
        }
        /**
         * @var User $user
         */
        $user = $this->users->get($id);
        $user->attachNetwork($network, $identity);
        $this->users->save($user);
    }
}