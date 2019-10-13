<?php

class m140725_122757_create_bonuses_items_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{bonuses_items}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'item_id' => 'int unsigned NOT NULL COMMENT \'ID предмета\'',
            'count' => 'int unsigned NOT NULL DEFAULT \'1\' COMMENT \'Кол-во\'',
            'enchant' => 'int unsigned NOT NULL DEFAULT \'0\' COMMENT \'Заточка\'',
            'bonus_id' => 'int unsigned NOT NULL COMMENT \'ID бонуса к которому прицеплен предмет\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{bonuses_items}}');
    }
}