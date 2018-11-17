<?php

namespace common\bootstrap;

use shop\repositories\UserRepository;
use shop\repositories\UserRepositoryInterface;
use shop\services\auth\AuthService;
use shop\services\auth\AuthServiceInterface;
use shop\services\auth\NetworkService;
use shop\services\auth\NetworkServiceInterface;
use shop\services\auth\PasswordResetServiceInterface;
use shop\services\auth\SignupService;
use shop\services\auth\SignupServiceInterface;
use shop\services\contact\ContactService;
use shop\services\contact\ContactServiceInterface;
use Yii;
use shop\services\auth\PasswordResetService;
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
        $container->setSingleton(NetworkServiceInterface::class, NetworkService::class);
        $container->setSingleton(AuthServiceInterface::class, AuthService::class);
        $container->setSingleton(SignupServiceInterface::class, function () use ($app, $container) {
            $users = $container->get(UserRepositoryInterface::class);
            return new SignupService(
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $app->mailer,
                'Signup confirm for ' . $app->name,
                $users
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
            $users = $container->get(UserRepositoryInterface::class);
            return new PasswordResetService(
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $app->mailer,
                'Password reset for ' . $app->name,
                $users
            );
        });
    }
}