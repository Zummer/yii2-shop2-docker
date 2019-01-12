<?php
namespace frontend\controllers\auth;

use common\auth\Identity;
use yii\helpers\Url;
use shop\services\auth\NetworkServiceInterface;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\authclient\AuthAction;

class NetworkController extends Controller
{
    private $service;
    public function __construct($id, $module, NetworkServiceInterface $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['auth/auth/login']),
            ],
        ];
    }
    public function onAuthSuccess(ClientInterface $client): void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');
        $email = '';

        switch($network) {
            case 'vk':
                $email = ArrayHelper::getValue($attributes, 'email');
                break;
            case 'yandex':
                $email = ArrayHelper::getValue($attributes, 'default_email');
                break;
            default:
                break;
        }

        try {
            $user = $this->service->auth($network, $identity, $email);
            Yii::$app->user->login(new Identity($user), Yii::$app->params['user.rememberMeDuration']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}
