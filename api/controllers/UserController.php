<?php
namespace api\controllers;

use api\helpers\DateHelper;
use api\providers\MapDataProvider;
use backend\forms\UserSearch;
use shop\entities\User\User;
use shop\forms\manage\User\UserCreateForm;
use shop\forms\manage\User\UserEditForm;
use shop\helpers\UserHelper;
use shop\repositories\UserRepository;
use shop\services\manage\UserManageServiceInterface;
use yii\data\DataProviderInterface;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'shop\entities\User\User';
    public $users;
    private $service;

    public function __construct(
        $id,
        $module,
        UserRepository $users,
        UserManageServiceInterface $service,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->users = $users;
        $this->service = $service;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['createUser'],
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'roles' => ['readUser'],
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => ['updateUser'],
                    'roleParams' => function() {
                        return ['userId' => \Yii::$app->request->get('id')];
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['deleteUser'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    public function actionView($id): array
    {
        $user = $this->users->get($id);

        return $this->serializeUser($user);
    }

    public function actionCreate(): array
    {
        $form = new UserCreateForm();
        $form->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if ($form->validate()) {
            try {
                $user = $this->service->create($form);
                return $this->serializeUser($user);
            } catch (\Exception $e) {
                throw new ServerErrorHttpException($e->getMessage());
            }
        } else {
            return $form->getErrors();
        }
    }

    public function actionUpdate($id): array
    {
        $user = $this->users->get($id);
        $form = new UserEditForm($user);
        $form->load(\Yii::$app->request->getBodyParams(), '');

        if ($form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                $user = $this->users->get($id);

                return $this->serializeUser($user);
            } catch (\Exception $e) {
                throw new ServerErrorHttpException($e->getMessage());
            }
        } else {
            return $form->getErrors();
        }

    }

    public function actionDelete($id): array
    {
        try {
            $this->service->remove($id);
            return [
                'message' => 'User ' . $id . ' successfully deleted.'
            ];
        } catch (\Exception $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }

    }

    public function prepareDataProvider(): DataProviderInterface
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return new MapDataProvider($dataProvider, [$this, 'serializeUser']);
    }

    public function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->username,
            'email' => $user->email,
            'date' => [
                'created' => DateHelper::formatApi($user->created_at),
                'updated' => DateHelper::formatApi($user->updated_at),
            ],
            'status' => [
                'code' => $user->status,
                'name' => UserHelper::statusName($user->status),
            ],
            'description' => $user->description,
        ];
    }
}
