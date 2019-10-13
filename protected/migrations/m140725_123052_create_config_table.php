<?php

class m140725_123052_create_config_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{config}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'param' => 'varchar(128) NOT NULL',
            'value' => 'text NOT NULL',
            'default' => 'text NOT NULL',
            'label' => 'varchar(255) NOT NULL',
            'group_id' => 'int unsigned NOT NULL COMMENT \'ID группы\'',
            'order' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Сортировка\'',
            'method' => 'varchar(54) DEFAULT NULL COMMENT \'Метод который будет вызван\'',
            'field_type' => 'varchar(54) NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`),
            UNIQUE KEY `param` (`param`)'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{config}}');
    }
}