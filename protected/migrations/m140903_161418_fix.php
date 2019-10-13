<?php

class m140903_161418_fix extends CDbMigration
{
    public function safeUp()
    {
        $this->alterColumn('{{gs}}', 'services_change_char_name_chars', 'varchar(255) NOT NULL COMMENT \'Символы которые можно ввести для нового ника\'');
        $this->alterColumn('{{tickets}}', 'status', 'tinyint(1) unsigned NOT NULL');
        $this->alterColumn('{{tickets}}', 'new_message_for_user', 'tinyint(1) unsigned NOT NULL');
        $this->alterColumn('{{tickets}}', 'new_message_for_admin', 'tinyint(1) unsigned NOT NULL');
    }

    public function safeDown()
    {
        $this->alterColumn('{{gs}}', 'services_change_char_name_chars', 'varchar(255) DEFAULT NULL COMMENT \'Символы которые можно ввести для нового ника\'');
        $this->alterColumn('{{tickets}}', 'status', 'tinyint(1) NOT NULL DEFAULT \'1\'');
        $this->alterColumn('{{tickets}}', 'new_message_for_user', 'tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'0 - нет, 1 - есть\'');
        $this->alterColumn('{{tickets}}', 'new_message_for_admin', 'tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'0 - нет, 1 - есть\'');
    }
}