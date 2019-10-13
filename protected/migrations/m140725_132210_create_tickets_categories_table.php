<?php

class m140725_132210_create_tickets_categories_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{tickets_categories}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(255) NOT NULL COMMENT \'Название\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'sort' => 'smallint(5) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Сортировка\'',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{tickets_categories}}');
    }
}