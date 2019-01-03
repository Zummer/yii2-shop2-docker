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
use shop\services\manage\UserManageService;
use shop\services\manage\UserManageServiceInterface;
use shop\services\RoleManager;
use shop\services\RoleManagerInterface;
use shop\services\TransactionManager;
use shop\services\TransactionManagerInterface;
use Yii;
use shop\services\auth\PasswordResetService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\base\Application;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;
        $container->setSingleton(TransactionManagerInterface::class, TransactionManager::class);
        $container->setSingleton(RoleManagerInterface::class, RoleManager::class);
        $container->setSingleton(UserManageServiceInterface::class, UserManageService::class);
        $container->setSingleton(UserRepositoryInterface::class, UserRepository::class);
        $container->setSingleton(NetworkServiceInterface::class, NetworkService::class);
        $container->setSingleton(AuthServiceInterface::class, AuthService::class);
        $container->setSingleton(SignupServiceInterface::class, function () use ($app, $container) {
            return new SignupService(
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $container->get(MailerInterface::class),
                'Signup confirm for ' . $app->name,
                $container->get(UserRepositoryInterface::class),
                $container->get(RoleManagerInterface::class),
                $container->get(TransactionManagerInterface::class)
            );
        });

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(ContactServiceInterface::class, function () use ($app, $container) {
            return new ContactService(
                $container->get(MailerInterface::class),
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $app->params['adminEmail']
            );

        });

        $container->setSingleton(PasswordResetServiceInterface::class, function () use ($app, $container) {
            return new PasswordResetService(
                [$app->params['supportEmail'] => $app->name . ' robot'],
                $container->get(MailerInterface::class),
                'Password reset for ' . $app->name,
                $container->get(UserRepositoryInterface::class)
            );
        });

        /**
         * AuthManager.
         */
        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });
    }
}