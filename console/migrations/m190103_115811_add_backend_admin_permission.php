<?php

use yii\db\Migration;

/**
 * Class m190103_115811_add_backend_admin_permission
 */
class m190103_115811_add_backend_admin_permission extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%auth_item}}', ['type', 'name', 'description'], [
            [2, 'backend_admin_permission', 'Backend Admin Permission'],
        ]);

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['admin', 'backend_admin_permission'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignment}} (item_name, user_id) VALUES (\'backend_admin_permission\', 1)');
    }

    public function down()
    {
        $this->delete('{{%auth_item}}', ['name' => ['backend_admin_permission']]);
    }
}
