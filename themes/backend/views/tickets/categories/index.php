<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $dataProvider
 * @var TicketsCategoriesSearch[] $data
 * @var TicketsCategoriesSearch $model
 */

use app\modules\backend\models\search\TicketsCategoriesSearch;

$title_ = 'Тикеты - категории';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo CHtml::link('Добавить новую категорию', ['form'], ['class' => 'btn btn-primary btn-block']) ?>
    <hr>

    <table class="table table-striped">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 10%;">
            <col style="width: 14%;">
            <col style="width: 10%;">
        </colgroup>
        <thead>
        <tr>
            <th width="5%">ID</th>
            <th>Название</th>
            <th width="10%">Статус</th>
            <th width="14%">Сортировка</th>
            <th width="10%"></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo e($row->title) ?></td>
                    <td><?php $this->widget('app.widgets.Status.Status', [
                            'status' => $row->status,
                            'statusText' => $row->getStatus()
                        ]) ?></td>
                    <td><?php echo $row->sort ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['form', 'id' => $row->getPrimaryKey()], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['allow', 'id' => $row->getPrimaryKey()], ['class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['del', 'id' => $row->getPrimaryKey()], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">Нет данных.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]);
