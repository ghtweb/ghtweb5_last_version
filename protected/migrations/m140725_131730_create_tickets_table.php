<?php

class m140725_131730_create_tickets_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{tickets}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL COMMENT \'ID того кто создал\'',
            'category_id' => 'smallint(5) unsigned NOT NULL COMMENT \'ID категории\'',
            'priority' => 'tinyint(3) unsigned NOT NULL COMMENT \'Приоритет\'',
            'date_incident' => 'varchar(128) NOT NULL COMMENT \'Дата происшествия\'',
            'char_name' => 'varchar(255) DEFAULT NULL COMMENT \'Имя персонажа\'',
            'title' => 'varchar(255) NOT NULL COMMENT \'Тема\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'new_message_for_user' => 'tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'0 - нет, 1 - есть\'',
            'new_message_for_admin' => 'tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'0 - нет, 1 - есть\'',
            'gs_id' => 'int(10) unsigned NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{tickets}}');
    }
}