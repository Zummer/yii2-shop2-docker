<?php

namespace shop\forms\manage\User;

use shop\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserEditForm extends Model
{
    public $username;
    public $email;
    public $role;
    public $description;

    public $_user;
    private $_manager;

    public function __construct(User $user, $config = [])
    {
        $this->description = $user->description;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->_manager = \Yii::$app->authManager;
        $roles = $this->_manager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['email', 'description'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map($this->_manager->getRoles(), 'name', 'description');
    }
}
