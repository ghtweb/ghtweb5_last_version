<?php

error_reporting(-1);

$rootPath = __DIR__ . '/..';
$protectedPath = $rootPath . '/protected';

// Проверка установлена ли CMS
if (is_dir($protectedPath . '/modules/install') && !is_file($protectedPath . '/config/lock') && strpos($_SERVER['REQUEST_URI'], 'install') === false) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/install/');
    exit;
}
// ---------------

// Путь к папке с framework
$yii = $rootPath . '/framework/yii.php';

if (!is_file($yii)) {
    die('Неправильно указан путь до папки <b>framework</b>');
}

// Чтобы включить режим разработки добавьте свой IP в массив тот что снизу, к примеру: ['127.0.0.1']
if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1'])) {
    define('YII_DEBUG', true);

    $config = $protectedPath . '/config/main-dev.php';
} else {
    error_reporting(0);

    define('YII_DEBUG', false);

    $config = $protectedPath . '/config/main.php';
}

// Запись в лог имени файла и номера строки
define('YII_TRACE_LEVEL', 1);

require_once $protectedPath . '/helpers/global.php';

require_once $rootPath . '/vendor/autoload.php';

require_once $yii;

$app = Yii::createWebApplication($config);

CHtml::setModelNameConverter(function($model) {
    $reflector = new ReflectionClass($model);
    return $reflector->getShortName();
});

$app->run();
