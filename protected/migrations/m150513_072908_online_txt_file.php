<?php

class m150513_072908_online_txt_file extends CDbMigration
{
    public function safeUp()
    {
        $order = $this->getDbConnection()
            ->createCommand("SELECT MAX(`order`) FROM {{config_group}}")
            ->queryScalar();

        $this->insert('{{config_group}}', [
            'name' => 'Файл online.txt',
            'order' => ++$order,
            'status' => 1,
        ]);

        $groupId = $this->getDbConnection()->lastInsertID;

        $order = 0;

        $this->insert('{{config}}', [
            'param' => 'online.txt.allow',
            'value' => 1,
            'default' => 1,
            'label' => 'Вкл. генерацию файла online.txt',
            'group_id' => $groupId,
            'order' => ++$order,
            'field_type' => 'dropDownList',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insert('{{config}}', [
            'param' => 'online.txt.time_refresh',
            'value' => 60,
            'default' => 60,
            'label' => 'Время через сколько обновятся данные в файле online.txt (в минутах)',
            'group_id' => $groupId,
            'order' => ++$order,
            'field_type' => 'textField',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->addColumn('{{gs}}', 'online_txt_allow', 'tinyint(1) unsigned DEFAULT 1 not null');
    }

    public function safeDown()
    {
        $this->delete('{{config_group}}', 'name = "Файл online.txt"');

        $this->delete('{{config}}', 'param = "online.txt.allow"');
        $this->delete('{{config}}', 'param = "online.txt.time_refresh"');

        $this->dropColumn('{{gs}}', 'online_txt_allow');
    }
}