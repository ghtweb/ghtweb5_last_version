<?php
/**
 * @var BonusesController $this
 * @var CActiveDataProvider $dataProvider
 * @var BonusesSearch $model
 * @var BonusesSearch[] $data
 * @var ActiveForm $form
 */

use app\modules\backend\models\search\BonusesSearch;

$title_ = 'Бонусы';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['index'],
]) ?>

<?php echo CHtml::link('Создать', ['add'], ['class' => 'btn btn-primary btn-block']) ?>
    <hr>
    <table class="table">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 15%;">
            <col style="width: 20%;">
            <col style="width: 10%;">
            <col style="width: 12%;">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Кол-во предметов</th>
            <th>Дата окончания</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'title', ['class' => 'form-control input-sm']) ?></td>
            <td></td>
            <td></td>
            <td><?php echo $form->dropDownList($model, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
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
                    <td><?php echo $row->itemCount ?></td>
                    <td><?php echo $row->getDateEnd() ?></td>
                    <td><?php $this->widget('app.widgets.Status.Status', [
                            'status' => $row->status,
                            'statusText' => $row->getStatus()
                        ]) ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['form', 'id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['/backend/bonusesItems/index', 'bonus_id' => $row->id], ['class' => 'glyphicon glyphicon-th', 'title' => 'Предметы для бонуса', 'rel' => 'tooltip']) ?></li>
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
]);
