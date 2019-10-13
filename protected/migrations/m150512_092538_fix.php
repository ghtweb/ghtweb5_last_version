<?php

class m150512_092538_fix extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{news}}', 'img', 'varchar(128)');

        $groupId = 3;

        $order = $this->getDbConnection()
            ->createCommand("SELECT MAX(`order`) FROM {{config}} WHERE group_id = :group_id")
            ->queryScalar([
                'group_id' => $groupId,
            ]);

        // Добавление настроек в конфиг
        $this->insert('{{config}}', [
            'param' => 'news.img.width',
            'value' => '150',
            'default' => '150',
            'label' => 'Ширина картинки для новости',
            'group_id' => $groupId,
            'order' => ++$order,
            'field_type' => 'textField',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->insert('{{config}}', [
            'param' => 'news.img.height',
            'value' => '150',
            'default' => '150',
            'label' => 'Высота картинки для новости',
            'group_id' => $groupId,
            'order' => ++$order,
            'field_type' => 'textField',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public function safeDown()
    {
        $this->dropColumn('{{news}}', 'img');

        $this->delete('{{config}}', 'param = "news.img.width"');
        $this->delete('{{config}}', 'param = "news.img.height"');
    }
}