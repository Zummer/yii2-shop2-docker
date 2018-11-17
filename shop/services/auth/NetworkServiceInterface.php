<?php
namespace shop\services\auth;

use shop\entities\User\User;

interface NetworkServiceInterface
{
    /**
     * @param $network
     * @param $identity
     * @param $email
     * @return User
     */
    public function auth($network, $identity, $email): User;

    /**
     * @param $id
     * @param $network
     * @param $identity
     */
    public function attach($id, $network, $identity): void;
}