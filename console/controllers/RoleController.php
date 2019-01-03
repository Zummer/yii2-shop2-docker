<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 02.01.19
 * Time: 20:16
 */

namespace console\controllers;

use shop\entities\User\User;
use shop\services\manage\UserManageServiceInterface;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use \Exception;

class RoleController extends Controller
{
    private $service;

    public function __construct($id, $module, UserManageServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionAssign(): void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $role = $this->select('Role:', ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Done!' . PHP_EOL);
    }

    private function findModel($username): User
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('User not found');
        }

        return $model;

    }
}