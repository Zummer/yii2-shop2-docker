<?php
namespace common\services;

use common\entities\User;
use common\forms\LoginForm;

interface AuthServiceInterface
{
    /**
     * @param LoginForm $form
     * @return User
     */
    public function auth(LoginForm $form): User;
}