<?php

use yii\db\Migration;

class m181114_170144_create_user_networks_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_networks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string(16)->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-user_networks-identity-name}}',
            '{{%user_networks}}',
            ['identity', 'network'],
            true
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_networks-user_id}}',
            '{{%user_networks}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_networks-user_id}}',
            '{{%user_networks}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_networks-user_id}}',
            '{{%user_networks}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_networks-user_id}}',
            '{{%user_networks}}'
        );

        $this->dropTable('{{%user_networks}}');
    }
}
