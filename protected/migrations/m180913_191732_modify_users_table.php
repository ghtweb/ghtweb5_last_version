<?php

class m180913_191732_modify_users_table extends CDbMigration
{
	public function up()
	{
	    $this->dropColumn('{{users}}', 'password');
        $this->addColumn('{{users}}', 'reset_password_hash', 'varchar(255) DEFAULT NULL');
	}

	public function down()
	{
		$this->addColumn('{{users}}', 'password', 'varchar(128) NOT NULL');
        $this->dropColumn('{{users}}', 'reset_password_hash');
	}
}