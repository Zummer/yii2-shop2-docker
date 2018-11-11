<?php
namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;
use yii\mail\MailerInterface;

class SignupService implements SignupServiceInterface
{
    private $mailer;
    private $supportEmail;
    /**
     * @var string
     */
    private $emailSubject;

    public function __construct(array $supportEmail, MailerInterface $mailer, string $emailSubject)
    {
        $this->mailer = $mailer;
        $this->supportEmail = $supportEmail;
        $this->emailSubject = $emailSubject;
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

        $this->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
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

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->save($user);
    }

    /**
     * @param User $user
     */
    private function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param string $token
     * @return User
     */
    private function getByEmailConfirmToken(string $token): User
    {
        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }
}