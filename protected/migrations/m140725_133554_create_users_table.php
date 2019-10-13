<?php

class m140725_133554_create_users_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{users}}', [
            'user_id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'login' => 'varchar(54) NOT NULL',
            'password' => 'varchar(128) NOT NULL',
            'email' => 'varchar(128) DEFAULT NULL',
            'activated' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
            'referer' => 'varchar(54) DEFAULT NULL COMMENT \'Реферальный код\'',
            'role' => 'varchar(24) NOT NULL DEFAULT \'user\'',
            'auth_hash' => 'char(32) DEFAULT NULL',
            'registration_ip' => 'varchar(54) DEFAULT NULL',
            'ls_id' => 'int unsigned NOT NULL COMMENT \'ID логина с аккаунтов\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`user_id`),
            KEY `ixUser_id` (`user_id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{users}}');
    }
}