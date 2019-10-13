<?php
/**
 * Файл который скачивается после реги
 *
 * @var Gs $gs
 * @var string $login
 * @var string $clear_login
 * @var string $password
 * @var string $email
 */
?>
Игровой сервер <?php echo CHtml::encode(config('meta.title')) ?>


Данные доступа на сайт:

Сервер: <?php echo e($gs->name) ?>

Логин: <?php echo $login ?>

Пароль: <?php echo $password ?>

E-mail: <?php echo $email ?>


Храните эти данные в надежном месте!