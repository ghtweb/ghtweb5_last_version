<?php

class m140725_133023_create_user_bonuses_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{user_bonuses}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'bonus_id' => 'int unsigned NOT NULL',
            'user_id' => 'int unsigned NOT NULL',
            'status' => 'tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'0 - не активирован, 1 - ативирован\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{user_bonuses}}');
    }
}