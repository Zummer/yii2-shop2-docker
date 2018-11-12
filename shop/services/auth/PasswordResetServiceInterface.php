<?php
namespace shop\services\auth;

use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;

interface PasswordResetServiceInterface
{
    public function request(PasswordResetRequestForm $form): void;

    public function validateToken(string $token): void;

    public function reset(string $token, ResetPasswordForm $form): void;
}