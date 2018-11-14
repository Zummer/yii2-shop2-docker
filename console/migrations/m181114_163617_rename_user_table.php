<?php

use yii\db\Migration;

class m181114_163617_rename_user_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

    public function down()
    {
        $this->renameTable('{{%users}}', '{{%user}}');

        return false;
    }
}
