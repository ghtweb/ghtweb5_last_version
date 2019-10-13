<?php

class m140725_132039_create_tickets_answers_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{tickets_answers}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'ticket_id' => 'int unsigned NOT NULL COMMENT \'ID тикета\'',
            'text' => 'text NOT NULL',
            'user_id' => 'int unsigned NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'PRIMARY KEY (`id`)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{tickets_answers}}');
    }
}