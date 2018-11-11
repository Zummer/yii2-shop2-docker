<?php

namespace common\bootstrap;

use common\repositories\UserRepository;
use common\repositories\UserRepositoryInterface;
use frontend\services\auth\PasswordResetServiceInterface;
use frontend\services\auth\SignupService;
use frontend\services\auth\SignupServiceInterface;
use frontend\services\contact\ContactService;
use frontend\services\contact\ContactServiceInterface;
use Yii;
use frontend\services\auth\PasswordResetService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\base\Application;

class SetUp implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(UserRepositoryInterface::class, UserRepository::class);

        $container->setSingleton(SignupServiceInterface::class, function () use ($app, $container) {
            return new SignupService(
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $app->mailer,
                'Signup confirm for ' . $app->name,
                $container->get(UserRepositoryInterface::class)
            );
        });

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(ContactServiceInterface::class, function () use ($app) {
            return new ContactService(
                $app->mailer,
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $app->params['adminEmail']
            );

        });

        $container->setSingleton(PasswordResetServiceInterface::class, function () use ($app, $container) {
            return new PasswordResetService(
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $app->mailer,
                'Password reset for ' . $app->name,
                $container->get(UserRepositoryInterface::class)
            );
        });
    }
}