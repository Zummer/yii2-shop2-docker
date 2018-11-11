<?php
namespace frontend\services\auth;

use frontend\forms\ResetPasswordForm;
use common\entities\User;
use frontend\forms\PasswordResetRequestForm;
use yii\mail\MailerInterface;

class PasswordResetService implements PasswordResetServiceInterface
{
    private $supportEmail;
    private $mailer;
    private $emailSubject;

    public function __construct(array $supportEmail, MailerInterface $mailer, string $emailSubject)
    {
        $this->supportEmail = $supportEmail;
        $this->mailer = $mailer;
        $this->emailSubject = $emailSubject;
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('User is not active.');
        }

        $user->requestPasswordReset();
        $this->save($user);

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
        if (!$this->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->save($user);
    }

    /**
     * @param $user
     */
    private function save($user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param string $email
     * @return User
     */
    private function getByEmail(string $email): User
    {
        $user = User::findOne(['email' => $email]);

        if (!$user) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

    /**
     * @param string $token
     * @return User|null
     */
    private function getByPasswordResetToken(string $token)
    {
        $user = User::findByPasswordResetToken($token);
        if (!$user) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

    /**
     * @param string $token
     * @return bool
     */
    private function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }
}