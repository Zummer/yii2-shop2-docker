<?php

namespace api\controllers;

use shop\entities\User\User;
use shop\forms\manage\User\UserEditForm;
use shop\helpers\UserHelper;
use shop\services\manage\UserManageServiceInterface;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class ProfileController extends Controller
{
    private $service;

    public function __construct($id, $module, UserManageServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

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

        return $this->serializeUser($user);
    }

    public function actionUpdate()
    {
        $user = $this->findModel();
        $form = new UserEditForm($user);
        $form->load(\Yii::$app->request->getBodyParams(), '');

        if ($form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                $user = $this->findModel();

                return $this->serializeUser($user);
            } catch (\Exception $e) {
                throw new ServerErrorHttpException($e->getMessage());
            }
        } else {
            return $form->getErrors();
        }
    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }

    public function findModel()
    {
        return User::findOne(\Yii::$app->user->id);
    }

    public function serializeUser(User $user): array
    {
        return [
            'id'=> $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => UserHelper::statusName($user->status),
            'description' => $user->description,
        ];
    }
}