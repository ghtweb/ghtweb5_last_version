<?php

class m140725_131457_create_shop_items_packs_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{shop_items_packs}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(255) NOT NULL COMMENT \'Название набора\'',
            'description' => 'text',
            'category_id' => 'int unsigned NOT NULL',
            'img' => 'varchar(128) DEFAULT NULL',
            'sort' => 'int unsigned NOT NULL DEFAULT \'1\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{shop_items_packs}}');
    }
}