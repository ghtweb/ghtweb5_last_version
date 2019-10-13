<?php

class m180913_220845_create_admin_table extends CDbMigration
{
	public function up()
	{
        Yii::import('application.components.ActiveRecord');
	    Yii::import('application.models.Users');

        $this->createTable('{{admin}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'login' => 'varchar(54) NOT NULL',
            'password' => 'varchar(128) NOT NULL',
            'auth_hash' => 'varchar(128)',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`),
            KEY `ix_login` (`login`) USING BTREE'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
	}

	public function down()
	{
        $this->dropTable('{{admin}}');
	}
}