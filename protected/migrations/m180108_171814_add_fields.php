<?php

class m180108_171814_add_fields extends CDbMigration
{
	public function safeUp()
	{
	    $this->addColumn('{{user_bonuses}}', 'char_name', 'varchar(128)');
	    $this->addColumn('{{bonus_codes}}', 'count_activation', 'integer(11) DEFAULT 0');
	}

	public function safeDown()
	{
        $this->dropColumn('{{user_bonuses}}', 'char_name');
        $this->dropColumn('{{bonus_codes}}', 'count_activation');
	}
}