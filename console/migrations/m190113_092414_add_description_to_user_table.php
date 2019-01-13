<?php

use yii\db\Migration;

/**
 * Class m190113_092414_add_description_to_user_table
 */
class m190113_092414_add_description_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'description', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'description');
    }
}
