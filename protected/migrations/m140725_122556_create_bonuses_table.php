<?php

class m140725_122556_create_bonuses_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{bonuses}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(255) NOT NULL COMMENT \'Название бонуса\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{bonuses}}');
    }
}