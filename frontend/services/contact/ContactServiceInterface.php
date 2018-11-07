<?php
namespace frontend\services\contact;

use frontend\forms\ContactForm;

interface ContactServiceInterface
{
    public function send(ContactForm $form): void;
}