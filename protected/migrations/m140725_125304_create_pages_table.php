<?php

class m140725_125304_create_pages_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{pages}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'page' => 'varchar(255) NOT NULL',
            'title' => 'varchar(255) NOT NULL',
            'text' => 'text NOT NULL',
            'seo_title' => 'varchar(255) DEFAULT NULL',
            'seo_keywords' => 'varchar(255) DEFAULT NULL',
            'seo_description' => 'varchar(255) DEFAULT NULL',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`),
            KEY `ix_page` (`page`) USING BTREE'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{pages}}');
    }
}