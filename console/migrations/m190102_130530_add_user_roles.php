<?php

use yii\db\Migration;

/**
 * Class m190102_130530_add_user_roles
 */
class m190102_130530_add_user_roles extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%auth_item}}', ['type', 'name', 'description'], [
            [1, 'user', 'User role'],
            [1, 'admin', 'Admin role'],
        ]);

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['admin', 'user'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignment}} (item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    public function down()
    {
        $this->delete('{{%auth_item}}', ['name' => ['user', 'admin']]);
    }
}
