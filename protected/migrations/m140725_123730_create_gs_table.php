<?php

class m140725_123730_create_gs_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{gs}}', [
            'id' => 'int UNSIGNED NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(54) NOT NULL',
            'ip' => 'varchar(54) NOT NULL',
            'port' => 'int unsigned NOT NULL',
            'db_host' => 'varchar(54) NOT NULL',
            'db_port' => 'int unsigned NOT NULL',
            'db_user' => 'varchar(54) NOT NULL',
            'db_pass' => 'varchar(54) DEFAULT NULL',
            'db_name' => 'varchar(54) NOT NULL',
            'telnet_host' => 'varchar(54) DEFAULT NULL',
            'telnet_port' => 'int unsigned DEFAULT NULL',
            'telnet_pass' => 'varchar(54) DEFAULT NULL',
            'login_id' => 'tinyint(3) unsigned NOT NULL',
            'version' => 'varchar(20) NOT NULL',
            'fake_online' => 'int unsigned NOT NULL DEFAULT \'0\'',
            'allow_teleport' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'teleport_time' => 'int unsigned NOT NULL DEFAULT \'20\'',
            'stats_allow' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_cache_time' => 'int unsigned NOT NULL DEFAULT \'10\'',
            'stats_total' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_pvp' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_pk' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_clans' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_castles' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_online' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_clan_info' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_top' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_rich' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'stats_count_results' => 'int unsigned NOT NULL DEFAULT \'20\'',
            'exp' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'sp' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'adena' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'drop' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'items' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'spoil' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'q_drop' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'q_reward' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'rb' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'erb' => 'varchar(6) NOT NULL DEFAULT \'1\'',
            'services_premium_allow' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Покупка премиума\'',
            'services_premium_cost' => 'text COMMENT \'Стоймость премиум аккаунта\'',
            'services_remove_hwid_allow' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Удаление привязки по HWID\'',
            'services_change_char_name_allow' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Смена имени персонажу\'',
            'services_change_char_name_cost' => 'float unsigned NOT NULL DEFAULT \'300\' COMMENT \'Стоймость смены ника персонажу\'',
            'services_change_char_name_chars' => 'varchar(255) NOT NULL COMMENT \'Символы которые можно ввести для нового ника\'',
            'services_change_gender_allow' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Смена пола персонажу\'',
            'services_change_gender_cost' => 'float unsigned NOT NULL DEFAULT \'300\' COMMENT \'Стоймость смены пола\'',
            'currency_name' => 'varchar(128) DEFAULT NULL COMMENT \'Название валюты сервера\'',
            'deposit_allow' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Вкл/Выкл возможность пополняться\'',
            'deposit_payment_system' => 'tinyint(3) unsigned NOT NULL DEFAULT \'0\' COMMENT \'Агрегатор\'',
            'deposit_desc' => 'text',
            'deposit_course_payments' => 'decimal(10,0) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Курс валюты к игровой валюте\'',
            'currency_symbol' => 'varchar(54) NOT NULL DEFAULT \'RUR\' COMMENT \'Валюта, RUR, EUR, USD и т.д\'',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`),
            KEY `ixId` (`id`)'
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('{{gs}}');
    }
}