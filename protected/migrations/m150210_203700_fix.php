<?php

class m150210_203700_fix extends CDbMigration
{
    public function up()
    {
        $this->insert('{{config}}', [
            'param' => 'forum_threads.characters_limit',
            'value' => 20,
            'default' => 20,
            'label' => 'До скольки символов обрезать название темы',
            'group_id' => 7,
            'order' => 14,
            'field_type' => 'textField',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->delete('{{config}}', 'param = "forum_threads.characters_limit"');
    }
}