<?php

return CMap::mergeArray(
    require dirname(__FILE__) . '/main.php',
    [
        'modules' => [

            'gii' => [
                'class' => 'system.gii.GiiModule',
                'password' => '123456',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => ['127.0.0.1', '::1'],
                'generatorPaths' => [
                    'ext.gii'
                ],
            ],

        ],
        'components' => [
            'cache' => [
                'class' => 'system.caching.CDummyCache',
            ],
            'log' => [
                'routes' => [
                    [
                        'class' => 'CWebLogRoute',
                        'levels' => 'error, warning, trace, notice',
                        'categories' => 'application',
                        'enabled' => true,
                    ],
                    [
                        'class' => 'CProfileLogRoute',
                        'levels' => 'profile',
                        'enabled' => true,
                    ],
                ],
            ],
        ],
        'params' => [],
    ]
);
