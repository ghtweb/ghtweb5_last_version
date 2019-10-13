<?php
/**
 * @var NewsController $this
 * @var NewsSearch $model
 * @var CActiveDataProvider $dataProvider
 * @var ActiveForm $form
 * @var News[] $data
 */

use app\modules\backend\models\search\NewsSearch;

$title_ = 'Новости';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['index'],
]) ?>

<?php echo CHtml::link('Создать новость', ['add'], ['class' => 'btn btn-primary btn-block']) ?>
<hr>
<table class="table">
    <colgroup>
        <col style="width: 5%">
        <col>
        <col style="width: 10%">
        <col style="width: 15%">
        <col style="width: 15%">
        <col style="width: 12%">
    </colgroup>
    <thead>
    <tr>
        <th>ID</th>
        <th>Заголовок</th>
        <th>Автор</th>
        <th>Статус</th>
        <th>Дата создания</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
        <td><?php echo $form->textField($model, 'title', ['class' => 'form-control input-sm']) ?></td>
        <td></td>
        <td><?php echo $form->dropDownList($model, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
        <td></td>
        <td>
            <button type="submit" class="btn btn-primary glyphicon glyphicon-search btn-sm" title="Искать"
                    rel="tooltip"></button>
            <?php echo CHtml::link('', ['index'], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle btn-sm', 'title' => 'Сбросить', 'rel' => 'tooltip']) ?>
        </td>
    </tr>
    <?php if ($data = $dataProvider->getData()) { ?>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?php echo $row->id ?></td>
                <td><?php echo e($row->title) ?></td>
                <td>
                    <?php echo !empty($row->author) ? CHtml::link($row->author->login, ['/backend/admins/edit', 'id' => $row->user_id], ['target' => '_blank']) : 'n/a' ?>
                </td>
                <td><?php $this->widget('app.widgets.Status.Status', [
                        'status' => $row->status,
                        'statusText' => $row->getStatus()
                    ]) ?></td>
                <td><?php echo $row->getCreatedAt('Y-m-d H:i') ?></td>
                <td>
                    <ul class="actions list-unstyled">
                        <li><?php echo CHtml::link('', ['form', 'id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['allow', 'id' => $row->id], ['class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['del', 'id' => $row->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                    </ul>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6">Нет данных.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>
