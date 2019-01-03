<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 02.01.19
 * Time: 12:42
 */

namespace console\controllers;

use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $am = \Yii::$app->authManager;
        $user = $am->createRole('user');
        $user->description = 'User Role';

        $am->add($user);
    }
}