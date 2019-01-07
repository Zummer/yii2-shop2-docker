<?php

use yii\db\Migration;

/**
 * Class m190105_101850_add_user_crud_permission
 */
class m190105_101850_add_user_crud_permission extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%auth_rule}}', [
            'name' => 'userOwner',
            'data' => 'O:25:"common\rbac\UserOwnerRule":3:{s:4:"name";s:9:"userOwner";s:9:"createdAt";N;s:9:"updatedAt";N;}',
        ]);

        $this->batchInsert('{{%auth_item}}', ['type', 'name', 'description', 'rule_name'], [
            [2, 'createUser', 'Create User Permission', null],
            [2, 'readUser', 'Read User Permission', null],
            [2, 'updateUser', 'Update User Permission', null],
            [2, 'updateOwnUser', 'Update Own User Permission', 'userOwner'],
            [2, 'deleteUser', 'Delete User Permission', null],
        ]);

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['updateOwnUser', 'updateUser'],
            ['admin', 'deleteUser'],
            ['admin', 'updateUser'],
            ['admin', 'createUser'],
            ['user', 'readUser'],
            ['user', 'updateOwnUser'],
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%auth_item}}', ['name' => ['createUser', 'readUser', 'updateUser', 'updateOwnUser', 'deleteUser']]);
        $this->delete('{{%auth_rule}}', ['name' => ['userOwner']]);
        $this->delete('{{%auth_item_child}}', ['child' => ['createUser', 'readUser', 'updateUser', 'updateOwnUser', 'deleteUser']]);
    }
}
