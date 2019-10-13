<?php

class m180201_195048_fix_theme_field extends CDbMigration
{
	public function up()
	{
        $this->update('{{config}}', ['value' => 'ghtweb', 'default' => 'ghtweb'], 'param = :param', ['param' => 'theme']);
	}

	public function down()
	{
	}
}