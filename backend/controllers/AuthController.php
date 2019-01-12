<?php
namespace backend\controllers;

use common\auth\Identity;
use shop\services\auth\AuthServiceInterface;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\forms\auth\LoginForm;

class AuthController extends Controller
{
    private $authService;
    public function __construct(
        $id,
        $module,
        AuthServiceInterface $service,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->authService = $service;
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'main-login';
        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                $duration = $form->rememberMe ? 3600 * 24 * 30 : 0;
                Yii::$app->user->login(new Identity($user), $duration);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('login', [
            'model' => $form,
        ]);
    }
    /**
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
