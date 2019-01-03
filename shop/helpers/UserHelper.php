<?php
namespace shop\helpers;

use shop\access\Rbac;
use shop\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;

class UserHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_WAIT => 'Wait',
            User::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function roleLabels($userId): string
    {

        $roles = \Yii::$app->authManager->getRolesByUser($userId);
        return $roles === [] ? 'no roles' : implode(' ', array_map(function (Item $role){
            return self::getRoleLabel($role);
        }, $roles));
    }

    public static function getRoleLabel(Item $role): string
    {
        switch ($role->name) {
            case Rbac::ROLE_USER:
                $class = 'primary';
                break;
            case Rbac::ROLE_ADMIN:
                $class = 'danger';
                break;
            default:
                $class = 'default';
        }
        return Html::tag('span', Html::encode($role->description), ['class' => 'label label-' . $class]);
    }
}