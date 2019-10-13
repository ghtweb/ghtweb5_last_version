<?php

return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'GHTWEB Console',

    // preloading 'log' component
    'preload' => ['log'],

    'commandMap' => [
        'migrate' => [
            'class' => 'system.cli.commands.MigrateCommand',
            //'migrationPath' => 'application.migrations',
            'migrationTable' => 'ghtweb_migration',
            //'connectionID' => 'db',
            //'templateFile' => 'application.migrations.template',
        ],
    ],

    // application components
    'components' => [

        'db' => require 'database.php',

        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ],
            ],
        ],
    ],
];