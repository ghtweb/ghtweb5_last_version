<?php
/**
 * @var string $content
 */

$assetsUrl = app()->getThemeManager()->getBaseUrl();
$assetsPath = Yii::getPathOfAlias('webroot') . '/themes/backend';

css($assetsUrl . '/css/login.css?v=' . filemtime($assetsPath . '/css/login.css'));
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>GHTWEB / Login</title>
</head>
<body>

<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li><a href="<?php echo app()->getBaseUrl(true) ?>">Вернуться на сайт</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">GHTWEB</h3>
    </div>

    <div class="jumbotron">
        <h1>Авторизация</h1>
        <?php echo $content ?>
    </div>
    <footer class="footer">
        <p>&copy; <?php echo date('Y') ?> <a href="https://cp.ghtweb.ru/" target="_blank">ghtweb.ru</a></p>
    </footer>
</div>
</body>
</html>