<?php

class m180106_194929_remove_unused_fields extends CDbMigration
{
    public function safeUp()
    {
        $this->dropColumn('{{user_profiles}}', 'preferred_language');
        $this->dropColumn('{{user_profiles}}', 'vote_balance');

        $this->dropColumn('{{news}}', 'lang');
        $this->dropColumn('{{pages}}', 'lang');

        $this->dropColumn('{{users}}', 'password');
        $this->addColumn('{{users}}', 'password', 'varchar(128)');

        $this->dropColumn('{{gs}}', 'services_premium_allow');
        $this->dropColumn('{{gs}}', 'services_premium_cost');
        $this->dropColumn('{{gs}}', 'services_remove_hwid_allow');
        $this->dropColumn('{{gs}}', 'services_change_char_name_allow');
        $this->dropColumn('{{gs}}', 'services_change_char_name_cost');
        $this->dropColumn('{{gs}}', 'services_change_char_name_chars');
        $this->dropColumn('{{gs}}', 'services_change_gender_allow');
        $this->dropColumn('{{gs}}', 'services_change_gender_cost');

        $this->dropColumn('{{purchase_items_log}}', 'currency_type');
        $this->dropColumn('{{shop_items}}', 'currency_type');
    }

    public function safeDown()
    {
        $this->addColumn('{{user_profiles}}', 'preferred_language', 'char(2) NOT NULL DEFAULT \'ru\' COMMENT \'Предпочитаемый язык\'');
        $this->addColumn('{{user_profiles}}', 'vote_balance', 'decimal(10,2) unsigned NOT NULL DEFAULT \'0.00\' COMMENT \'Сколько раз проголосовал в рейтингах\'');

        $this->addColumn('{{news}}', 'lang', 'char(2) DEFAULT "ru"');
        $this->addColumn('{{pages}}', 'lang', 'char(2) DEFAULT "ru"');

        $this->dropColumn('{{users}}', 'password');
        $this->addColumn('{{users}}', 'password', 'varchar(128) NOT NULL');

        $this->addColumn('{{gs}}', 'services_premium_allow', 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Покупка премиума\'');
        $this->addColumn('{{gs}}', 'services_premium_cost', 'text COMMENT \'Стоймость премиум аккаунта\'');
        $this->addColumn('{{gs}}', 'services_remove_hwid_allow', 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Удаление привязки по HWID\'');
        $this->addColumn('{{gs}}', 'services_change_char_name_allow', 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Смена имени персонажу\'');
        $this->addColumn('{{gs}}', 'services_change_char_name_cost', 'float unsigned NOT NULL DEFAULT \'300\' COMMENT \'Стоймость смены ника персонажу\'');
        $this->addColumn('{{gs}}', 'services_change_char_name_chars', 'varchar(255) NOT NULL COMMENT \'Символы которые можно ввести для нового ника\'');
        $this->addColumn('{{gs}}', 'services_change_gender_allow', 'tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'Смена пола персонажу\'');
        $this->addColumn('{{gs}}', 'services_change_gender_cost', 'float unsigned NOT NULL DEFAULT \'300\' COMMENT \'Стоймость смены пола\'');

        $this->addColumn('{{purchase_items_log}}', 'currency_type', 'varchar(54) NOT NULL DEFAULT \'donat\'');
        $this->addColumn('{{shop_items}}', 'currency_type', 'varchar(54) NOT NULL DEFAULT \'donat\' COMMENT \'За какой тип валюты отдать предмет, vote - за голоса с рейтингов, donat - за рил бабки\'');
    }
}
