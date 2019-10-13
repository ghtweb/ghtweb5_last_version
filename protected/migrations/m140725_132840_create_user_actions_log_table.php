<?php

class m140725_132840_create_user_actions_log_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{user_actions_log}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL',
            'action_id' => 'smallint(5) unsigned NOT NULL COMMENT \'ID того что сделал юзер\'',
            'params' => 'text COMMENT \'Параметры совершаемого действия\'',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`),
            KEY `IxUserId` (`user_id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{user_actions_log}}');
    }
}