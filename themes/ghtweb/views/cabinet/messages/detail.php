<?php
/**
 * @var UserMessages $model
 */

$title_ = 'Личные сообщения';
$this->pageTitle = $title_;

$this->breadcrumbs = [
    $title_ => ['/cabinet/messages/index'],
    $model->getShortMessage(5)
];
?>

<h3>Информация</h3>
<p>
    <b>Дата создания:</b> <?php echo date('Y-m-d H:i', strtotime($model->created_at)) ?>
    <br>
    <b>Статус:</b> <?php echo e($model->read == UserMessages::STATUS_NOT_READ ? 'Не прочитано' : 'Прочитано') ?>
    <br>
    <b>Сообщение:</b><br>
    <?php echo nl2br(e($model->message)) ?><br>
</p>
