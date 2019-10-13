<?php

class m140725_123539_create_gallery_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{gallery}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(255) DEFAULT NULL',
            'img' => 'varchar(54) NOT NULL',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'sort' => 'smallint(5) unsigned NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{gallery}}');
    }
}