<?php

$backendUrl = 'backend'; // Ссылка на админку (не менять!!!)

Yii::setPathOfAlias('themes', __DIR__ . '/../../themes');
Yii::setPathOfAlias('TaggedCache', __DIR__ . '/../extensions/yii-cache-tag-dependency');

return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'GHTWEB v5',
    'sourceLanguage' => '00',
    'language' => 'ru',

    'aliases' => [
        'app' => 'application',
        'widgets' => 'application.widgets',
        'modules' => 'application.modules',
        'validators' => 'application.validators',
    ],

    //'basePath' => 'app',

    // preloading 'log' component
    'preload' => ['log'],

    'import' => [
        'application.helpers.*',
        'application.models.*',
        'application.components.*',
        'application.l2j.AbstractQuery',
    ],

    'modules' => [

        'register',
        'login',
        'forgottenPassword',
        'news',
        'page',
        'cabinet',
        'index',
        'backend',
        'deposit',
        'stats',
        'gallery',
        'install',

    ],

    // application components
    'components' => [

        'clientScript' => [
            'scriptMap' => [
                'jquery.js' => false,
            ],
        ],

        'widgetFactory' => [

            //'enableSkin' => TRUE,
            //'skinPath' => 'widgets',

            'widgets' => [

                'CCaptcha' => [
                    'clickableImage' => true,
                    'showRefreshButton' => false,
                    'imageOptions' => [
                        'title' => 'Refresh',
                    ],
                ],

                // Настройки для виджета пагинации
                'CLinkPager' => [
                    'header' => '',
                    'footer' => '',
                    'hiddenPageCssClass' => 'disabled',
                    'firstPageLabel' => '&lt;&lt;',
                    'prevPageLabel' => '&lt;',
                    'nextPageLabel' => '&gt;',
                    'lastPageLabel' => '&gt&gt',
                    'maxButtonCount' => 7,
                    //'cssFile'             => '/css/pagination.css',
                    'id' => 'pagination',
                    'selectedPageCssClass' => 'active',
                    'htmlOptions' => [
                        'class' => 'pagination pagination-sm',
                    ]
                    //'internalPageCssClass' => '',
                ],

                // Настройки для виджета breadcrumb
                'CBreadcrumbs' => [
                    'tagName' => 'ul',
                    'separator' => '',
                    'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
                    'inactiveLinkTemplate' => '<li>{label}</li>',
                    'htmlOptions' => [
                        'class' => 'breadcrumb',
                    ],
                ],
            ],
        ],

        'notify' => [
            'class' => 'Notify',
        ],

        'config' => [
            'class' => 'DbConfig',
        ],

        'cache' => [
            'class' => 'system.caching.CFileCache',
        ],

        'user' => [
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
            'identityCookie' => ['name' => 'gw_user'],
            'loginUrl' => ['/login/default/index'],
        ],

        'admin' => [
            'class' => 'WebAdmin',
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
            'identityCookie' => ['name' => 'gw_admin'],
            'loginUrl' => ['/' . $backendUrl . '/login/index'],
        ],

        'authManager' => [
            'class' => 'CDbAuthManager',
            'connectionId' => 'db',
        ],

        'securityManager' => [
            'cryptAlgorithm' => ['rijndael-256', '', 'ofb', ''],
            'encryptionKey' => '7fzyTAybk70bzuLf', // Длина ключа должна быть ровно 16, 24 или 32 символа
        ],

        'request' => [
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'GHTWEB_CSRF_TOKEN',
        ],

        'urlManager' => [
            'urlFormat' => 'path',
            'showScriptName' => false,
            'urlSuffix' => '/',
            'rules' => [

                // ------------------- [Frontend] -------------------
                '' => 'index/default/index',

                // Deposit (обработка платежа)
                'deposit/result' => 'deposit/default/index',

                // Статические страницы
                'page/<page_name:[a-z0-9-_]+>' => 'page/default/index',

                // Новости
                'news' => 'news/default/index',
                'news/<slug:[a-z0-9-_]+>' => 'news/default/detail',

                // Регистрация
                'register' => 'register/default/index',
                'register/<_hash:\w{32}>' => 'register/default/activated',

                // Авторизация
                'login' => 'login/default/index',

                // Восстановление пароля
                'forgotten-password' => 'forgottenPassword/default/index',
                'forgotten-password/<hash:[a-z0-9\_]+>' => 'forgottenPassword/default/step2',

                // Deposit
                'cabinet/deposit' => 'cabinet/deposit/index',
                'cabinet/deposit/processed' => 'cabinet/deposit/processed',
                'deposit/<action:(result|success|fail)>' => 'deposit/default/<action>',
                'cabinet/deposit/sms-list' => 'cabinet/deposit/getSmsNumberList',

                // Logout
                'logout' => 'cabinet/default/logout',


                // Статистика
                'stats/<gs_id:\d+>/<type:([a-z\-]+)>/<clan_id:\w+>' => 'stats/default/index',
                'stats/<gs_id:\d+>/<type:\w+>' => 'stats/default/index',
                'stats/<gs_id:\d+>' => 'stats/default/index',
                'stats' => 'stats/default/index',


                // Cabinet
                'cabinet' => 'cabinet/default/index',

                // Персонажи
                'cabinet/characters' => 'cabinet/characters/index',
                'cabinet/characters/<char_id:([0-9]+)>/<action:(view|teleport)>' => 'cabinet/characters/<action>',

                // Смена пароля от аккаунта
                'cabinet/change-password' => 'cabinet/changePassword/index',

                // Безопасность
                'cabinet/security' => 'cabinet/security/index',

                // Рефералы
                'cabinet/referals' => 'cabinet/referals/index',

                // История платежей
                'cabinet/transaction-history' => 'cabinet/transactionHistory/index',

                // История авторизаций
                'cabinet/auth-history' => 'cabinet/authHistory/index',

                // Магазин
                'cabinet/shop/<category_link:([a-zA-Z0-9\-]+)>' => 'cabinet/shop/index',
                'cabinet/shop' => 'cabinet/shop/index',
                'cabinet/shop/<category_link:([a-zA-Z0-9\-]+)>/buy' => 'cabinet/shop/buy',

                // Тикеты
                'cabinet/tickets' => 'cabinet/tickets/index',
                'cabinet/tickets/add' => 'cabinet/tickets/add',
                'cabinet/tickets/<ticket_id:\d+>/<action:(view|close)>' => 'cabinet/tickets/<action>',

                // Бонусы
                'cabinet/bonuses' => 'cabinet/bonuses/index',
                'cabinet/bonuses/bonus-code' => 'cabinet/bonuses/bonusCode',

                // Личные сообщения
                'cabinet/messages' => 'cabinet/messages/index',
                'cabinet/messages/<id:\d+>/detail' => 'cabinet/messages/detail',

                // Галерея
                'gallery' => 'gallery/default/index',


                // ------------------- [Backend] -------------------
                $backendUrl => 'backend/default/index',

                // Авторизация
                $backendUrl . '/login' => 'backend/login/index',

                // Выход из админки
                $backendUrl . '/logout' => 'backend/login/logout',

                // Очистка кэша
                $backendUrl . '/clear-cache' => 'backend/default/clearCache',

                // Инфа о item
                $backendUrl . '/get-item-info' => 'backend/default/getItemInfo',

                // Игровые сервера
                $backendUrl . '/game-servers' => 'backend/gameServers/index',
                $backendUrl . '/game-servers/<gs_id:\d+>/edit' => 'backend/gameServers/form',
                $backendUrl . '/game-servers/add' => 'backend/gameServers/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/<action:(del|allow)>' => 'backend/gameServers/<action>',

                // Магазин
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories' => 'backend/gamesServerShopCategories/index',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/edit' => 'backend/gamesServerShopCategories/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/add' => 'backend/gamesServerShopCategories/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/<action:allow|del>' => 'backend/gamesServerShopCategories/<action>',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs' => 'backend/gamesServerShopCategoriesPacks/index',

                // Магазин - наборы
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/edit' => 'backend/gamesServerShopCategoriesPacks/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/add' => 'backend/gamesServerShopCategoriesPacks/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/<action:allow|del>' => 'backend/gamesServerShopCategoriesPacks/<action>',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/del-img' => 'backend/gamesServerShopCategoriesPacks/delImg',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/items' => 'backend/gamesServerShopCategoriesPacksItems/index',

                // Магазин - наборы - предметы
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/items/<item_id:\d+>/edit' => 'backend/gamesServerShopCategoriesPacksItems/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/items/add' => 'backend/gamesServerShopCategoriesPacksItems/form',
                $backendUrl . '/game-servers/<gs_id:\d+>/shop-categories/<category_id:\d+>/packs/<pack_id:\d+>/items/<item_id:\d+>/<action:del|allow>' => 'backend/gamesServerShopCategoriesPacksItems/<action>',


                // Логин сервера
                $backendUrl . '/login-servers' => 'backend/loginServers/index',
                $backendUrl . '/login-servers/<ls_id:\d+>/edit' => 'backend/loginServers/form',
                $backendUrl . '/login-servers/add' => 'backend/loginServers/form',
                $backendUrl . '/login-servers/<ls_id:\d+>/<action:(del|allow|accounts)>' => 'backend/loginServers/<action>',

                // Игровые аккаунты на логине
                // $backendUrl . '/login-servers/<ls_id:\d+>/<action:(edit|del|shop)>' => 'backend/loginServers/<action>',

                // Юзеры
                $backendUrl . '/users' => 'backend/users/index',
                $backendUrl . '/users/add' => 'backend/users/add',
                $backendUrl . '/users/<user_id:\d+>/<action:(view|referals)>' => 'backend/users/<action>',
                $backendUrl . '/users/<user_id:\d+>/auth-history' => 'backend/users/authHistory',
                $backendUrl . '/users/<user_id:\d+>/delete-bonus/<bonus_id:\d+>' => 'backend/users/delBonus',
                $backendUrl . '/users/<user_id:\d+>/add-bonus' => 'backend/users/addBonus',
                $backendUrl . '/users/<user_id:\d+>/add-message' => 'backend/users/addMessage',
                $backendUrl . '/users/<user_id:\d+>/item-purchase' => 'backend/users/itemPurchaseLog',
                $backendUrl . '/users/<user_id:\d+>/transaction-history' => 'backend/users/transactionHistory',
                $backendUrl . '/users/<user_id:\d+>/edit-data' => 'backend/users/editData',

                // Бонусы, предметы в бонусе
                $backendUrl . '/bonuses/<bonus_id:\d+>/items' => 'backend/bonusesItems/index',
                $backendUrl . '/bonuses/<bonus_id:\d+>/items/<item_id:\d+>/edit' => 'backend/bonusesItems/form',
                $backendUrl . '/bonuses/<bonus_id:\d+>/items-create' => 'backend/bonusesItems/form',
                $backendUrl . '/bonuses/<bonus_id:\d+>/items/<item_id:\d+>/del' => 'backend/bonusesItems/del',
                $backendUrl . '/bonuses/<bonus_id:\d+>/items/<item_id:\d+>/allow' => 'backend/bonusesItems/allow',

                // Коды для бонусов
                $backendUrl . '/bonuses/generate-code' => 'backend/bonusCodes/generateCode',

                $backendUrl . '/bonuses/codes' => 'backend/bonusCodes/index',
                $backendUrl . '/bonuses/codes/<id:\d+>/edit' => 'backend/bonusCodes/form',
                $backendUrl . '/bonuses/codes/add' => 'backend/bonusCodes/form',
                $backendUrl . '/bonuses/codes/<id:\d+>/allow' => 'backend/bonusCodes/allow',
                $backendUrl . '/bonuses/codes/<id:\d+>/del' => 'backend/bonusCodes/del',

                // Тикеты
                $backendUrl . '/tickets/<id:\d+>/edit' => 'backend/tickets/edit',

                // Тикеты - категории
                $backendUrl . '/tickets/categories' => 'backend/ticketsCategories/index',
                $backendUrl . '/tickets/categories/add' => 'backend/ticketsCategories/form',
                $backendUrl . '/tickets/categories/<id:\d+>/edit' => 'backend/ticketsCategories/form',
                $backendUrl . '/tickets/categories/<id:\d+>/del' => 'backend/ticketsCategories/del',
                $backendUrl . '/tickets/categories/<id:\d+>/allow' => 'backend/ticketsCategories/allow',

                // admin
                $backendUrl . '/admins/add' => 'backend/admins/add',
                $backendUrl . '/admins/<id:\d+>/edit' => 'backend/admins/edit',


                // Общие правила
                $backendUrl . '/<controller:\w+>' => 'backend/<controller>/index',
                $backendUrl . '/<controller:\w+>/<id:\d+>/edit' => 'backend/<controller>/form',
                $backendUrl . '/<controller:\w+>/add' => 'backend/<controller>/form',
                $backendUrl . '/<controller:\w+>/<id:\d+>/<method:(del|allow)>' => 'backend/<controller>/<method>',
                //$backendUrl . '/<controller:\w+>/<method:(del|edit|allow)>' => 'backend/<controller>/<method>',
            ],
        ],

        // DB
        'db' => require __DIR__ . '/database.php',

        'errorHandler' => [
            'errorAction' => 'index/default/error',
        ],

        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CFileLogRoute',
                    'levels' => 'info, error, warning, vardump',
                ],
                [
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, warning, trace, notice',
                    'categories' => 'application',
                    'enabled' => false,
                ],
                [
                    'class' => 'CProfileLogRoute',
                    'levels' => 'profile',
                    'enabled' => false,
                ],
            ],
        ],
    ],

    'params' => require __DIR__ . '/params.php',
];
