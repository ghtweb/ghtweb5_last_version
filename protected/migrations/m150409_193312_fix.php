<?php

class m150409_193312_fix extends CDbMigration
{
    public function safeUp()
    {
        $this->delete('{{config}}', 'param = :param', ['param' => 'cabinet.change_password.captcha.allow']);

        // Избавляюсь от telnet (наконецто =)))
        $this->dropColumn('{{gs}}', 'telnet_host');
        $this->dropColumn('{{gs}}', 'telnet_port');
        $this->dropColumn('{{gs}}', 'telnet_pass');

        $this->dropColumn('{{ls}}', 'telnet_host');
        $this->dropColumn('{{ls}}', 'telnet_port');
        $this->dropColumn('{{ls}}', 'telnet_pass');
    }

    public function safeDown()
    {
        $this->insert('{{config}}', [
            'param' => 'cabinet.change_password.captcha.allow',
            'value' => 0,
            'default' => 0,
            'label' => 'Капча при смене пароля от аккаунта',
            'group_id' => 16,
            'order' => 4,
            'method' => null,
            'field_type' => 'dropDownList',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->addColumn('{{gs}}', 'telnet_host', 'varchar(54)');
        $this->addColumn('{{gs}}', 'telnet_port', 'int unsigned');
        $this->addColumn('{{gs}}', 'telnet_pass', 'varchar(54)');

        $this->addColumn('{{ls}}', 'telnet_host', 'varchar(54)');
        $this->addColumn('{{ls}}', 'telnet_port', 'int unsigned');
        $this->addColumn('{{ls}}', 'telnet_pass', 'varchar(54)');
    }
}