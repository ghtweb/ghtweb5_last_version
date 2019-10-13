<?php

class m140725_125428_create_purchase_items_log_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{purchase_items_log}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'pack_id' => 'int unsigned NOT NULL COMMENT \'ID набора\'',
            'item_id' => 'int unsigned NOT NULL COMMENT \'ID предмета\'',
            'description' => 'text',
            'cost' => 'decimal(10,2) unsigned NOT NULL COMMENT \'Стоймость\'',
            'discount' => 'float unsigned NOT NULL DEFAULT \'0\' COMMENT \'Скидка на товар\'',
            'currency_type' => 'varchar(54) NOT NULL DEFAULT \'donat\'',
            'count' => 'int unsigned NOT NULL DEFAULT \'1\' COMMENT \'Кол-во\'',
            'enchant' => 'smallint(5) unsigned NOT NULL COMMENT \'Заточка\'',
            'user_id' => 'int unsigned NOT NULL COMMENT \'ID того кто купил\'',
            'char_id' => 'int unsigned NOT NULL COMMENT \'ID персонажа которому упала шмотка\'',
            'gs_id' => 'int unsigned NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{purchase_items_log}}');
    }
}