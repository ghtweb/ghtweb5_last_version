<?php

Yii::import('system.cli.commands.MigrateCommand');

class MyMigrateCommand extends MigrateCommand
{
    public $migrationTable = 'ghtweb_migration';
}
