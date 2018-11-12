<?php
namespace shop\services\contact;

use shop\forms\ContactForm;

interface ContactServiceInterface
{
    public function send(ContactForm $form): void;
}
