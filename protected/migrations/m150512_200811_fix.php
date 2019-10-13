<?php

class m150512_200811_fix extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{news}}', 'lang', 'char(2) DEFAULT "ru"');
        $this->addColumn('{{pages}}', 'lang', 'char(2) DEFAULT "ru"');
    }

    public function safeDown()
    {
        $this->dropColumn('{{news}}', 'lang');
        $this->dropColumn('{{pages}}', 'lang');
    }
}