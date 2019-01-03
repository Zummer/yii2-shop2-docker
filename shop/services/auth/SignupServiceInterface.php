<?php
namespace shop\services\auth;

use shop\forms\auth\SignupForm;

interface SignupServiceInterface
{
    public function signup(SignupForm $form): void;
    public function confirm(string $token): void;
}