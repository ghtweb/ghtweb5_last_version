<?php

class m140725_133210_create_user_messages_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{user_messages}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL',
            'message' => 'text NOT NULL',
            'read' => 'tinyint(1) unsigned NOT NULL DEFAULT \'0\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{user_messages}}');
    }
}