<?php

class m140725_134052_create_users_auth_logs_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{users_auth_logs}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL',
            'ip' => 'varchar(25) NOT NULL',
            'user_agent' => 'varchar(255) DEFAULT NULL',
            'status' => 'tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'0 - Пароль введен не верно, 1 - Авторация прошла удачно\'',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{users_auth_logs}}');
    }
}