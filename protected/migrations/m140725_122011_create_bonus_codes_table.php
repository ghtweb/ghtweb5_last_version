<?php

class m140725_122011_create_bonus_codes_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{bonus_codes}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'bonus_id' => 'int unsigned NOT NULL',
            'code' => 'varchar(128) NOT NULL COMMENT \'Бонус код\'',
            'limit' => 'int unsigned NOT NULL DEFAULT \'1\' COMMENT \'Кол-во активироваций\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{bonus_codes}}');
    }
}