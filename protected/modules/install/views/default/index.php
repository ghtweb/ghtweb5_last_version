<?php

use app\helpers\Html;

$rootDir = Yii::getPathOfAlias('app');
$webDir = Yii::getPathOfAlias('webroot');
$error = false;
?>
    <div class="page-header">
        <h1>Шаг 1, проверка системы</h1>
    </div>

    <div class="alert alert-info">
        <h4>Внимание!</h4>
        - Убедитесь что в базе данных куда будет установлен сайт не содержатся таблицы с префиксом <b>ghtweb_</b><br>
        - Пользователь от базы данных должен имень права на <b>SELECT, UPDATE, INSERT ,DELETE, TRUNCATE</b><br>
    </div>

    <h3>Проверка прав на запись</h3>

    <ul>
        <?php

        $folders = [
            $webDir . '/assets',
            $rootDir . '/config',
            $rootDir . '/runtime',
            $webDir . '/uploads/images/gallery',
            $webDir . '/uploads/images/shop',
            $webDir . '/uploads/images/shop/packs',
            $rootDir . '/config/database.php'
        ];

        foreach ($folders as $folder) {
            $isWritable = Html::isWritable($folder);

            if ($isWritable === false) {
                $error = true;
            }

            $folder = str_replace("\\", '/', $folder);

            echo '<li>' . $folder . ' <span class="label label-' . ($isWritable ? 'success' : 'danger') . '">' . ($isWritable ? 'OK' : 'Установите права на запись 0777') . '</span></li>';
        }
        ?>
    </ul>

<?php

if ($error === false) {
    echo CHtml::link('Шаг 2', ['step2'], ['class' => 'btn btn-primary']);
}
