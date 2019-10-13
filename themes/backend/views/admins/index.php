<?php
/**
 * @var AdminsController $this
 * @var CActiveDataProvider $dataProvider
 * @var AdminSearch $model
 * @var ActiveForm $form
 * @var Admin[] $data
 */

use app\modules\backend\models\search\AdminSearch;

$title_ = 'Админы сайта';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['index'],
]) ?>

<?php echo CHtml::link('Создать', ['form'], ['class' => 'btn btn-primary btn-block']) ?>

    <hr>

    <table class="table">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 15%;">
            <col style="width: 7%;">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Дата создания</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'login', ['class' => 'form-control input-sm']) ?></td>
            <td colspan="2" style="text-align: right">
                <button type="submit" class="btn btn-primary glyphicon glyphicon-search btn-sm" title="Искать"></button>
                <?php echo CHtml::link('', ['index'], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle btn-sm', 'title' => 'Сбросить']) ?>
            </td>
        </tr>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo $row->login ?></td>
                    <td><?php echo $row->created_at ?></td>
                    <td style="text-align: right">
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['form', 'id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['del', 'id' => $row->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4">Нет данных.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>