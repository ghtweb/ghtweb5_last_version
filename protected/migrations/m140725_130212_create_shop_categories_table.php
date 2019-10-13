<?php

class m140725_130212_create_shop_categories_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{shop_categories}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(255) NOT NULL',
            'link' => 'varchar(255) NOT NULL COMMENT \'Ссылка к примеру "armor"\'',
            'sort' => 'int unsigned NOT NULL DEFAULT \'1\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'gs_id' => 'smallint(1) unsigned NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{shop_categories}}');
    }
}