<?php
namespace frontend\controllers\auth;

use common\auth\Identity;
use shop\services\auth\AuthServiceInterface;
use Yii;
use yii\web\Controller;
use shop\forms\auth\LoginForm;

class AuthController extends Controller
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
    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                $duration = $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0;
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
