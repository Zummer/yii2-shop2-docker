<?php
namespace api\controllers;

use common\auth\Token;
use shop\services\auth\AuthServiceInterface;
use Yii;
use yii\rest\Controller;
use shop\forms\auth\LoginForm;
use yii\web\UnprocessableEntityHttpException;

class SiteController extends Controller
{
    private $service;
    public function __construct(
        $id,
        $module,
        AuthServiceInterface $service,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionIndex()
    {
        return 'api';
    }

    public function actionLogin()
    {
        $form = new LoginForm();
        $form->load(Yii::$app->request->bodyParams, '');

        if ($form->validate()) {
            try {
                $user = $this->service->auth($form);
                $token = new Token();
                $token->user_id = $user->id;
                $token->generateToken(time() + 3600 * 24);
                return $token->save() ? [
                    'token' => $token->token,
                    'expired' => date(DATE_RFC3339, $token->expired_at),
                ] : $token;
            } catch (\Exception $e) {
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        } else {
            return $form;
        }
    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
        ];
    }
}