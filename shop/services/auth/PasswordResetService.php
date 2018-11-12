<?php
namespace shop\services\auth;

use shop\services\auth\PasswordResetServiceInterface;
use shop\repositories\UserRepository;
use shop\forms\auth\ResetPasswordForm;
use shop\forms\auth\PasswordResetRequestForm;
use yii\mail\MailerInterface;

class PasswordResetService implements PasswordResetServiceInterface
{
    private $supportEmail;
    private $mailer;
    private $emailSubject;
    private $users;

    public function __construct(
        array $supportEmail,
        MailerInterface $mailer,
        string $emailSubject,
        UserRepository $users
    )
    {
        $this->supportEmail = $supportEmail;
        $this->mailer = $mailer;
        $this->emailSubject = $emailSubject;
        $this->users = $users;
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('User is not active.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
            ->setTo($user->email)
            ->setSubject($this->emailSubject)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }

    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }
}

