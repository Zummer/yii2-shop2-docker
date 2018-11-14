<?php
namespace shop\services\auth;

use shop\entities\User\User;
use shop\forms\auth\LoginForm;

interface AuthServiceInterface
{
    /**
     * @param LoginForm $form
     * @return \shop\entities\User\User
     */
    public function auth(LoginForm $form): User;
}