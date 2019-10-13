<?php

class m140725_133335_create_user_profiles_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{user_profiles}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL',
            'balance' => 'decimal(10,2) unsigned NOT NULL DEFAULT \'0.00\' COMMENT \'Кол-во валюты\'',
            'vote_balance' => 'decimal(10,2) unsigned NOT NULL DEFAULT \'0.00\' COMMENT \'Сколько раз проголосовал в рейтингах\'',
            'preferred_language' => 'char(2) NOT NULL DEFAULT \'ru\' COMMENT \'Предпочитаемый язык\'',
            'protected_ip' => 'text COMMENT \'IP адреса которые могут зайти в ЛК\'',
            'phone' => 'varchar(54) DEFAULT NULL COMMENT \'Телефон юзера\'',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{user_profiles}}');
    }
}