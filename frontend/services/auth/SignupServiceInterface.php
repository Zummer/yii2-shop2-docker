<?php
namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;

interface SignupServiceInterface
{
    public function signup(SignupForm $form): void;
}