<?php
/**
 * @var string $content
 * @var int $countTickets
 */

$assetsUrl = app()->getThemeManager()->getBaseUrl();
$assetsPath = Yii::getPathOfAlias('webroot') . '/themes/backend';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>GHTWEB / Backend</title>
    <meta name="theme-color" content="#F8F8F8">
    <link rel="stylesheet"
          href="<?php echo $assetsUrl . '/css/common.css?v=' . filemtime($assetsPath . '/css/common.css') ?>">
    <script src="<?php echo $assetsUrl . '/js/runtime.js?v=' . filemtime($assetsPath . '/js/runtime.js') ?>"></script>
    <script src="<?php echo $assetsUrl . '/js/vendor.js?v=' . filemtime($assetsPath . '/js/vendor.js') ?>"></script>
    <script src="<?php echo $assetsUrl . '/js/common.js?v=' . filemtime($assetsPath . '/js/common.js') ?>"></script>
    <script>
        var CSRF_TOKEN_NAME = '<?php echo request()->csrfTokenName ?>',
            CSRF_TOKEN_VALUE = '<?php echo request()->csrfToken ?>';
    </script>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <div class="container">
            <?php $this->widget('zii.widgets.CMenu', [
                'encodeLabel' => false,
                'htmlOptions' => [
                    'class' => 'nav navbar-nav',
                ],
                'items' => [
                    [
                        'label' => 'Главная',
                        'url' => ['/backend/default/index'],
                    ],
                    [
                        'label' => 'Страницы',
                        'url' => ['/backend/pages/index'],
                        'active' => in_array(app()->getController()->getId(), ['pages']),
                    ],
                    [
                        'label' => 'Новости',
                        'url' => ['/backend/news/index'],
                        'active' => in_array(app()->getController()->getId(), ['news']),
                    ],
                    // ------
                    [
                        'label' => 'Юзеры <b class="caret"></b>',
                        'url' => '#',
                        'submenuOptions' => [ // LI > UL
                            'class' => 'dropdown-menu',
                        ],
                        'itemOptions' => [ // LI
                            'class' => 'dropdown',
                        ],
                        'linkOptions' => [
                            'class' => 'dropdown-toggle', // LI > A
                            'data-toggle' => 'dropdown',
                        ],
                        'active' => in_array(app()->getController()->getId(), ['users', 'admins']),
                        'items' => [
                            [
                                'label' => 'Юзеры сайта',
                                'url' => ['/backend/users/index'],
                            ],
                            [
                                'label' => 'Админы сайта',
                                'url' => ['/backend/admins/index'],
                                'visible' => in_array(admin()->getId(), app()->params['superAdminId']),
                            ],
                        ],
                    ],
                    // ------
                    [
                        'label' => 'Настройки',
                        'url' => ['/backend/config/index'],
                    ],
                    [
                        'label' => 'Lineage <b class="caret"></b>',
                        'url' => '#',
                        'submenuOptions' => [ // LI > UL
                            'class' => 'dropdown-menu',
                        ],
                        'itemOptions' => [ // LI
                            'class' => 'dropdown',
                        ],
                        'linkOptions' => [
                            'class' => 'dropdown-toggle', // LI > A
                            'data-toggle' => 'dropdown',
                        ],
                        'active' => in_array(app()->getController()->getId(), ['gameServers', 'loginServers']),
                        'items' => [
                            [
                                'label' => 'Игровые сервера',
                                'url' => ['/backend/gameServers/index'],
                            ],
                            [
                                'label' => 'Логин сервера',
                                'url' => ['/backend/loginServers/index'],
                            ],
                        ],
                    ],
                    [
                        'label' => 'Пополнения баланса',
                        'url' => ['/backend/transactions/index'],
                    ],
                    [
                        'label' => 'Галерея',
                        'url' => ['/backend/gallery/index'],
                        'active' => in_array(app()->getController()->getId(), ['gallery']),
                    ],
                    [
                        'label' => 'Бонусы <b class="caret"></b>',
                        'url' => '#',
                        'submenuOptions' => [ // LI > UL
                            'class' => 'dropdown-menu',
                        ],
                        'itemOptions' => [ // LI
                            'class' => 'dropdown',
                        ],
                        'linkOptions' => [
                            'class' => 'dropdown-toggle', // LI > A
                            'data-toggle' => 'dropdown',
                        ],
                        'active' => in_array(app()->getController()->getId(), ['bonuses', 'bonusCodes', 'bonusesItems']),
                        'items' => [
                            [
                                'label' => 'Просмотр',
                                'url' => ['/backend/bonuses/index'],
                            ],
                            [
                                'label' => 'Коды',
                                'url' => ['/backend/bonusCodes/index'],
                            ],
                        ],
                    ],
                    [
                        'label' => '<span class="count-tickets label label-primary" rel="tooltip" title="Новые тикеты">' . Yii::app()->params['countTickets'] . '</span>Тикеты <b class="caret"></b>',
                        'url' => '#',
                        'submenuOptions' => [ // LI > UL
                            'class' => 'dropdown-menu',
                        ],
                        'itemOptions' => [ // LI
                            'class' => 'dropdown',
                        ],
                        'linkOptions' => [
                            'class' => 'dropdown-toggle', // LI > A
                            'data-toggle' => 'dropdown',
                        ],
                        'active' => in_array(app()->getController()->getId(), ['tickets', 'ticketsCategories']),
                        'items' => [
                            [
                                'label' => 'Просмотр',
                                'url' => ['/backend/tickets/index'],
                            ],
                            [
                                'label' => 'Категории',
                                'url' => ['/backend/ticketsCategories/index'],
                            ],
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</nav>

<div class="wrapper">

    <section class="site-container container">
        <div class="page-header">
            <?php $this->widget('zii.widgets.CBreadcrumbs', [
                'links' => $this->breadcrumbs,
                'homeLink' => '<li>' . CHtml::link('Главная', ['/backend/default/index']) . '</li>',
            ]) ?>
        </div>
        <?php echo $content ?>
    </section>

    <div class="push"></div>
</div>

<footer class="site-footer">
    <div class="container">&copy; <a href="https://cp.ghtweb.ru" target="_blank">ghtweb.ru v5</a></div>
</footer>

<script type="template" id="modal-box-tpl">
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span>
                    </button>
                    <h4 class="modal-title">{{{ title }}}</h4>
                </div>
                <div class="modal-body">{{{ body }}}</div>
            </div>
        </div>
    </div>
</script>

</body>
</html>