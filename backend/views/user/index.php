<?php

use kartik\date\DatePicker;
use shop\entities\User\User;
use shop\helpers\UserHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use backend\widgets\grid\RoleColumn;
use shop\access\Rbac;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php if (\Yii::$app->user->can(Rbac::PERMISSION_BACKEND_ADMIN)): ?>
        <p>
            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'created_at',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd',
                            ],
                        ]),
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'username',
                        'value' => function (User $model) {
                            return Html::a(Html::encode($model->username), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw'
                    ],
                    'email:email',
                    [
                        'attribute' => 'role',
                        'class' => RoleColumn::class,
                        'filter' => $searchModel->rolesList(),
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => UserHelper::statusList(),
                        'value' => function (User $model) {
                            return UserHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'visibleButtons' => [
                             'update' => \Yii::$app->user->can(Rbac::PERMISSION_BACKEND_ADMIN),
                             'delete' => \Yii::$app->user->can(Rbac::PERMISSION_BACKEND_ADMIN),
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
