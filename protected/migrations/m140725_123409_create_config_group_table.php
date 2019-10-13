<?php

class m140725_123409_create_config_group_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{config_group}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'name' => 'text NOT NULL COMMENT \'Название группы\'',
            'order' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Сортировка\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{config_group}}');
    }
}