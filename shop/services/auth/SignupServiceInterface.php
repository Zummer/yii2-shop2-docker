<?php
namespace shop\services\auth;

use shop\entities\User;
use shop\forms\auth\SignupForm;

interface SignupServiceInterface
{
    public function signup(SignupForm $form): void;
}