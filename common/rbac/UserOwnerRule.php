<?php
namespace common\rbac;

use yii\base\InvalidCallException;
use yii\rbac\Rule;

class UserOwnerRule extends Rule
{
    public $name = 'userOwner';

    public function execute($userId, $item, $params)
    {
        if (empty($params['userId'])) {
            throw new InvalidCallException('Specify user.');
        }

        return $params['userId'] == $userId;
    }
}