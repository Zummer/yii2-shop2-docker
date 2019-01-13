<?php

namespace api\controllers;

use shop\entities\User\User;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class ProfileController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $user = $this->findModel();

        return [
            'id'=> $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->isActive() ? 'active' : 'not active'
        ];
    }

    public function findModel()
    {
        return User::findOne(\Yii::$app->user->id);
    }
}