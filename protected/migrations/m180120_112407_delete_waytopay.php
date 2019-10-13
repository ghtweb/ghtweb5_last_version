<?php

class m180120_112407_delete_waytopay extends CDbMigration
{
	public function safeUp()
	{
        Yii::import('application.components.ActiveRecord');
        Yii::import('application.models.ConfigGroup');
	    Yii::import('application.models.Config');

	    $model = ConfigGroup::model()->find('name = "Платежная система: Waytopay"');

	    if (!$model) {
	        return;
        }

	    Config::model()->deleteAll('group_id = :id', [
            ':id' => $model->id,
        ]);

	    $model->delete();
	}

	public function safeDown()
	{
        $this->insert('{{config_group}}', [
            'name' => 'Платежная система: Waytopay',
            'order' => 9,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = [
            ['waytopay.project_id', '', '', 'ID проекта', $insertId, 1, '', 'textField'],
            ['waytopay.key', '', '', 'Ключ', $insertId, 2, '', 'textField'],
            ['waytopay.sms.allow', 0, 0, 'SMS включить сервис', $insertId, 3, '', 'textField'],
            ['waytopay.sms.key', '', '', 'SMS ключ', $insertId, 4, '', 'textField'],
            ['waytopay.sms.project_id', '', '', 'SMS ID проекта', $insertId, 5, '', 'textField'],
        ];

        foreach ($insertData as $k => $v) {
            $insertData[$k] = [
                'param' => $v[0],
                'value' => $v[1],
                'default' => $v[2],
                'label' => $v[3],
                'group_id' => $v[4],
                'order' => $v[5],
                'method' => $v[6],
                'field_type' => $v[7],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $builder = $this->getDbConnection()->getSchema()->getCommandBuilder();
        $builder->createMultipleInsertCommand('{{config}}', $insertData)
            ->execute();
	}
}