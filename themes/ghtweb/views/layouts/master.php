<?php
/**
 * @var FrontendBaseController $this
 * @var string $content
 */

$assetsUrl = app()->getThemeManager()->getBaseUrl();

// -------------------- [Подключение jQuery] --------------------
js('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
// --------------------------------------------------------------

// -------------------- [Подключение шрифта] --------------------
css('//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic');
// ---------------------------------------------------------------------------------

// -------------------- [Подключение bootstrap, see: http://getbootstrap.com/] --------------------
css($assetsUrl . '/css/bootstrap.min.css');
js('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', CClientScript::POS_END);
// ---------------------------------------------------------------------------------

// -------------------- [Подключение либы для оформления <select>] --------------------
css($assetsUrl . '/js/libs/chosen/1.1.0/chosen.css');
js($assetsUrl . '/js/libs/chosen/1.1.0/chosen.jquery.min.js', CClientScript::POS_END);
// ------------------------------------------------------------------------------------

// -------------------- [Подключение вертикального скроллера] --------------------
css($assetsUrl . '/js/libs/scrollpane/jquery.jscrollpane.css');
js($assetsUrl . '/js/libs/scrollpane/jquery.mousewheel.js', CClientScript::POS_END);
js($assetsUrl . '/js/libs/scrollpane/jquery.jscrollpane.min.js', CClientScript::POS_END);
// -------------------------------------------------------------------------------

// -------------------- [Подключение стилей и скриптов от шаблона] --------------------
js($assetsUrl . '/js/main.js', CClientScript::POS_END);
css($assetsUrl . '/css/reset.css');
css($assetsUrl . '/css/main.css');
// ------------------------------------------------------------------------------------
?>
<!doctype html>
<html lang="<?php echo app()->language ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($this->getPageTitle()) ?></title>
    <meta name="theme-color" content="#0B0B0B">
    <meta name="description" content="<?php echo(!empty($this->metaDescription) ? e($this->metaDescription) : e(config('meta.description'))) ?>">
    <meta name="keywords" content="<?php echo(!empty($this->metaKeywords) ? e($this->metaKeywords) : e(config('meta.keywords'))) ?>">
</head>
<body>

<?php

// Виджет быстрых сообщений
$this->widget('app.widgets.UserNotifications.UserNotifications') ?>

<div id="layout">
    <div class="header-wrap">
        <header role="banner">
            <h1 class="logo">
                <a href="<?php echo app()->getBaseUrl(true) ?>"></a>
            </h1>
        </header>
    </div>
    <nav>
        <?php

        // Виджет для формирования меню
        $this->widget('zii.widgets.CMenu', [
            'htmlOptions' => [
                'class' => '',
            ],
            'items' => [
                [
                    'label' => 'Главная',
                    'url' => ['/index/default/index'],
                    'linkOptions' => [
                        'data-text' => 'Главная'
                    ],
                ],
                [
                    'label' => 'Регистрация',
                    'url' => ['/register/default/index'],
                    'linkOptions' => [
                        'data-text' => 'Регистрация'
                    ],
                ],
                [
                    'label' => 'Статистика',
                    'url' => ['/stats/default/index'],
                    'linkOptions' => [
                        'data-text' => 'Статистика',
                    ],
                ],
                [
                    'label' => 'О Сервере',
                    'url' => ['/page/default/index', 'page_name' => 'about'],
                    'linkOptions' => [
                        'data-text' => 'О Сервере'
                    ],
                ],
                [
                    'label' => 'Галерея',
                    'url' => ['/gallery/default/index'],
                    'linkOptions' => [
                        'data-text' => 'Галерея'
                    ],
                ],
                [
                    'label' => 'Форум',
                    'url' => 'https://forum.ghtweb.ru/',
                    'linkOptions' => [
                        'data-text' => 'Форум',
                        'target' => '_blank'
                    ],
                ],
            ]
        ]) ?>
    </nav>
    <div class="article-wrap">
        <article class="main clearfix" role="main">
            <div class="sidebar">
                <aside class="server-status">
                    <h2>Статус сервера</h2>
                    <?php

                    // Виджет статуса сервера
                    $this->widget('app.widgets.ServerStatus.ServerStatus') ?>
                </aside>

                <?php if (!user()->isGuest) { ?>
                    <aside class="menu">
                        <h2>Личный кабинет</h2>
                        <p class="gold"><span>Баланс:</span>
                            <b><?php echo formatCurrency(user()->get('balance')) ?></b></p>
                        <?php

                        // Виджет для формирования меню
                        $this->widget('zii.widgets.CMenu', [
                            'htmlOptions' => [
                                'class' => 'cabinet-menu',
                            ],
                            'items' => [
                                [
                                    'label' => 'Главная',
                                    'url' => ['/cabinet/default/index']
                                ],
                                [
                                    'label' => 'Пополнить баланс',
                                    'url' => ['/cabinet/deposit/index']
                                ],
                                [
                                    'label' => 'Магазин',
                                    'url' => ['/cabinet/shop/index']
                                ],
                                [
                                    'label' => 'Смена пароля',
                                    'url' => ['/cabinet/changePassword/index']
                                ],
                                [
                                    'label' => 'Безопасность',
                                    'url' => ['/cabinet/security/index']
                                ],
                                [
                                    'label' => 'Персонажи',
                                    'url' => ['/cabinet/characters/index']
                                ],
                                [
                                    'label' => 'Поддержка (' . Tickets::getCountNewMessagesForUser() . ')',
                                    'url' => ['/cabinet/tickets/index']
                                ],
                                [
                                    'label' => 'Реферальная программа',
                                    'url' => ['/cabinet/referals/index'],
                                    'visible' => config('referral_program.allow'),
                                ],
                                [
                                    'label' => 'Мои бонусы',
                                    'url' => ['/cabinet/bonuses/index'],
                                ],
                                [
                                    'label' => 'Ввести бонус код',
                                    'url' => ['/cabinet/bonuses/bonusCode'],
                                ],
                                [
                                    'label' => 'История пополнений',
                                    'url' => ['/cabinet/transactionHistory/index'],
                                    'visible' => config('deposit.allow')
                                ],
                                [
                                    'label' => 'История авторизаций',
                                    'url' => ['/cabinet/authHistory/index'],
                                    'visible' => config('deposit.allow')
                                ],
                                [
                                    'label' => 'Личные сообщения',
                                    'url' => ['/cabinet/messages/index'],
                                ],
                                [
                                    'label' => 'Выход',
                                    'url' => ['/cabinet/default/logout']
                                ],
                            ]
                        ]) ?>
                    </aside>
                <?php } else { ?>
                    <aside class="join">
                        <div class="block">
                            <?php echo CHtml::link('Личный кабинет', ['/login/default/index']) ?>
                        </div>
                    </aside>
                <?php } ?>
                <aside class="forum-threats">
                    <h2>Темы с форума</h2>
                    <?php

                    // Виджет тем с форума
                    $this->widget('app.widgets.ForumThreads.ForumThreads') ?>
                </aside>
            </div>
            <div class="content">

                <?php if (strpos($_SERVER['REQUEST_URI'], 'cabinet') !== false) { ?>
                    <div class="breadcrumbs">
                        <?php

                        // Виджет хлебных крошек
                        $this->widget('zii.widgets.CBreadcrumbs', [
                            'homeLink' => '<li>' . CHtml::link('Главная', ['/cabinet/default/index']) . '</li>',
                            'links' => $this->breadcrumbs,
                            'separator' => '<li class="divider">\</li>',
                        ]) ?>
                    </div>
                <?php } ?>

                <?php if ($_SERVER['REQUEST_URI'] == '/') {
                    // Виджет таймера обратного отсчета
                    $this->widget('app.widgets.Timer.Timer', [
                        'timeEnd' => strtotime('2020-01-01 00:00:00'), // Дата старта
                    ]);
                } ?>

                <?php echo $content ?>

            </div>
        </article>
    </div>
</div>
</body>
</html>

<!-- GHTWEB v<?php echo (new \app\services\VersionService())->getVersion() ?> -->