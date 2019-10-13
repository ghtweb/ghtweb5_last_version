<?php

class m171217_195456_news_add_slug extends CDbMigration
{
    public function up()
    {
        $this->addColumn('{{news}}', 'slug', 'varchar(125)');
    }

    public function down()
    {
        $this->dropColumn('{{news}}', 'slug');
    }
}