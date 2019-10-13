<?php

class m140806_193126_fix extends CDbMigration
{
    public function safeUp()
    {
        Yii::import('application.components.ActiveRecord');
        Yii::import('application.models.ConfigGroup');

        $groupId = ConfigGroup::model()->find('name = "Капча"');

        $this->insert('{{config}}', [
            'param' => 'captcha.bg.color',
            'value' => '#2D1A13',
            'default' => '#FFFFFF',
            'label' => 'Задний фон капчи',
            'group_id' => $groupId->id,
            'order' => 5,
            'method' => '',
            'field_type' => 'textField',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insert('{{config}}', [
            'param' => 'captcha.font.color',
            'value' => '#FFFFFF',
            'default' => '#000000',
            'label' => 'Цвет текста',
            'group_id' => $groupId->id,
            'order' => 5,
            'method' => '',
            'field_type' => 'textField',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $groupId = ConfigGroup::model()->find('name = "Регистрация"');

        $this->insert('{{config}}', [
            'param' => 'register.multiemail',
            'value' => 0,
            'default' => 0,
            'label' => 'Разрешить регистрировать на один Email много аккаунтов',
            'group_id' => $groupId->id,
            'order' => 5,
            'method' => '',
            'field_type' => 'dropDownList',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{config}}', 'param = :param', ['param' => 'captcha.bg.color']);
        $this->delete('{{config}}', 'param = :param', ['param' => 'captcha.font.color']);
        $this->delete('{{config}}', 'param = :param', ['param' => 'register.multiemail']);
    }
}