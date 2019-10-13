<?php

class m140725_125815_create_referals_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{referals}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'referer' => 'int unsigned NOT NULL COMMENT \'ID кто пригласил\'',
            'referal' => 'int unsigned NOT NULL COMMENT \'ID кого пригласили\'',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{referals}}');
    }
}