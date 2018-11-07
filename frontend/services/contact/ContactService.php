<?php
namespace frontend\services\contact;

use frontend\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactService implements ContactServiceInterface
{
    private $mailer;
    private $supportEmail;
    private $adminEmail;

    public function __construct(MailerInterface $mailer, $supportEmail, $adminEmail)
    {

        $this->mailer = $mailer;
        $this->supportEmail = $supportEmail;
        $this->adminEmail = $adminEmail;
    }

    public function send(ContactForm $form): void
    {
        $send =  $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setFrom($this->supportEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if (!$send) {
            throw new \RuntimeException('Sending error.');
        }
    }
}