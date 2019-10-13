<?php

class m140725_130712_create_shop_items_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{shop_items}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'pack_id' => 'int unsigned NOT NULL COMMENT \'ID набора\'',
            'item_id' => 'int unsigned NOT NULL COMMENT \'ID предмета\'',
            'description' => 'text',
            'cost' => 'decimal(10,2) unsigned NOT NULL COMMENT \'Стоймость\'',
            'discount' => 'float unsigned NOT NULL DEFAULT \'0\' COMMENT \'Скидка на товар\'',
            'currency_type' => 'varchar(54) NOT NULL DEFAULT \'donat\' COMMENT \'За какой тип валюты отдать предмет, vote - за голоса с рейтингов, donat - за рил бабки\'',
            'count' => 'int unsigned NOT NULL DEFAULT \'1\' COMMENT \'Кол-во\'',
            'enchant' => 'smallint(5) unsigned NOT NULL DEFAULT \'0\' COMMENT \'Заточка\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'sort' => 'int unsigned NOT NULL DEFAULT \'1\' COMMENT \'Сортировка\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{shop_items}}');
    }
}