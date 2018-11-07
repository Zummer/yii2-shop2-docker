<?php
namespace frontend\services\auth;

use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;

interface PasswordResetServiceInterface
{
    public function request(PasswordResetRequestForm $form): void;

    public function validateToken(string $token): void;

    public function reset(string $token, ResetPasswordForm $form): void;
}