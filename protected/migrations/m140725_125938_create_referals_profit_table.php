<?php

class m140725_125938_create_referals_profit_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{referals_profit}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'referer_id' => 'int unsigned NOT NULL COMMENT \'ID юзера который привёл\'',
            'referal_id' => 'int unsigned NOT NULL COMMENT \'ID юзера кто совершил сделку\'',
            'profit' => 'float unsigned NOT NULL COMMENT \'Прибыль, % от суммы пополнения\'',
            'sum' => 'float unsigned NOT NULL COMMENT \'На какую сумму совершен платеж\'',
            'percent' => 'float unsigned NOT NULL COMMENT \'% который был на момент совершения платежа\'',
            'transaction_id' => 'int unsigned NOT NULL COMMENT \'ID транзакции по которой было зачисление\'',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{referals_profit}}');
    }
}