<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 03.01.19
 * Time: 0:22
 */

namespace shop\services\manage;

use shop\entities\User\User;
use shop\forms\manage\User\UserCreateForm;
use shop\forms\manage\User\UserEditForm;

interface UserManageServiceInterface
{
    public function create(UserCreateForm $form): User;

    public function assignRole($id, $role): void;

    public function edit($id, UserEditForm $form): void;

    public function remove($id): void;
}