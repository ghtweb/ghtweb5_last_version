<?php

class m140725_132408_create_transactions_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{transactions}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'payment_system' => 'varchar(15) NOT NULL COMMENT \'Платёжная система (robokassa, waytopay и т.д)\'',
            'user_id' => 'varchar(54) NOT NULL',
            'sum' => 'decimal(10,0) unsigned NOT NULL COMMENT \'Сумма\'',
            'count' => 'int unsigned NOT NULL COMMENT \'Кол-во игровой валюты\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'user_ip' => 'varchar(54) DEFAULT NULL',
            'params' => 'text COMMENT \'Параметры которые прилетели\'',
            'gs_id' => 'int unsigned NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{transactions}}');
    }
}