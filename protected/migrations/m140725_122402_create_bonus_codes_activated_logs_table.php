<?php

class m140725_122402_create_bonus_codes_activated_logs_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{bonus_codes_activated_logs}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'code_id' => 'int unsigned NOT NULL',
            'user_id' => 'int unsigned NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{bonus_codes_activated_logs}}');
    }
}