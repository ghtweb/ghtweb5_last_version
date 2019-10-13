<?php

class m140725_121311_create_news_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{news}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL COMMENT \'ID автора\'',
            'title' => 'varchar(255) NOT NULL',
            'description' => 'text NOT NULL',
            'text' => 'text NOT NULL',
            'seo_title' => 'varchar(255) DEFAULT NULL',
            'seo_keywords' => 'varchar(255) DEFAULT NULL',
            'seo_description' => 'varchar(255) DEFAULT NULL',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`),
            KEY `ix_id` (`id`) USING BTREE'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{news}}');
    }
}