<?php
namespace api\controllers;

use api\helpers\DateHelper;
use api\providers\MapDataProvider;
use backend\forms\UserSearch;
use shop\entities\User\User;
use shop\helpers\UserHelper;
use shop\repositories\UserRepository;
use yii\data\DataProviderInterface;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'shop\entities\User\User';
    public $users;

    public function __construct(
        $id,
        $module,
        UserRepository $users,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->users = $users;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
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
        ];
    }
}