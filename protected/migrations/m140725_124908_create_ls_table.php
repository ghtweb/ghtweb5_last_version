<?php

class m140725_124908_create_ls_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{ls}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(54) NOT NULL',
            'ip' => 'varchar(54) NOT NULL',
            'port' => 'varchar(5) NOT NULL',
            'db_host' => 'varchar(54) NOT NULL',
            'db_port' => 'int unsigned NOT NULL',
            'db_user' => 'varchar(54) NOT NULL',
            'db_pass' => 'varchar(54) DEFAULT NULL',
            'db_name' => 'varchar(54) NOT NULL',
            'telnet_host' => 'varchar(54) DEFAULT NULL',
            'telnet_port' => 'int unsigned DEFAULT NULL',
            'telnet_pass' => 'varchar(54) DEFAULT NULL',
            'version' => 'varchar(20) NOT NULL',
            'password_type' => 'varchar(15) NOT NULL',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`),
            KEY `ix_id` (`id`) USING BTREE'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{ls}}');
    }
}