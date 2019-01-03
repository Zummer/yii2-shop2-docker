<?php
namespace shop\services\auth;

use shop\access\Rbac;
use shop\entities\User\User;
use shop\repositories\UserRepositoryInterface;
use shop\forms\auth\SignupForm;
use shop\services\RoleManagerInterface;
use shop\services\TransactionManagerInterface;
use yii\mail\MailerInterface;

class SignupService implements SignupServiceInterface
{
    private $mailer;
    private $supportEmail;
    private $emailSubject;
    private $users;
    private $roles;
    private $transaction;

    public function __construct(
        array $supportEmail,
        MailerInterface $mailer,
        string $emailSubject,
        UserRepositoryInterface $users,
        RoleManagerInterface $roles,
        TransactionManagerInterface $transaction
    )
    {
        $this->mailer = $mailer;
        $this->supportEmail = $supportEmail;
        $this->emailSubject = $emailSubject;
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function signup(SignupForm $form): void
    {
        if (User::find()->andWhere(['username' => $form->username])->one()) {
            throw new \DomainException('Username already exists.');
        }

        if (User::find()->andWhere(['email' => $form->email])->one()) {
            throw new \DomainException('Email already exists.');
        }

        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });


        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($form->email)
            ->setSubject($this->emailSubject)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm(string $token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }
}