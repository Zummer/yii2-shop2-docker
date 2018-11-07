<?php
namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;

class SignupService implements SignupServiceInterface
{
    public function signup(SignupForm $form): User
    {
        if (User::find()->andWhere(['username' => $form->username])->one()) {
            throw new \DomainException('Username already exists.');
        }

        if (User::find()->andWhere(['email' => $form->email])->one()) {
            throw new \DomainException('Email already exists.');
        }

        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );

        if (!$user->save()) {
            throw new \RuntimeException('Saving Error');
        }

        return $user;
    }
}