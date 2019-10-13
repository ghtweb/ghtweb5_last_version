<?php
/**
 * @var BonusesController $this
 * @var Bonuses $bonusModel
 * @var CActiveDataProvider $dataProvider
 * @var BonusesItems[] $data
 */

$title_ = 'Бонусы';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    $title_ => ['/backend/bonuses/index'],
    $bonusModel->title,
];
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo CHtml::link('Добавить предмет', ['/backend/bonusesItems/form', 'bonus_id' => $bonusModel->id], ['class' => 'btn btn-primary btn-block']) ?>
    <hr>

    <table class="table">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 10%;">
            <col style="width: 5%;">
            <col style="width: 10%;">
            <col style="width: 12%;">
        </colgroup>
        <thead>
        <tr>
            <th width="5%"></th>
            <th>Название</th>
            <th width="10%">Кол-во</th>
            <th width="5%">Заточка</th>
            <th width="10%">Статус</th>
            <th width="12%"></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><?php echo $row->itemInfo->getIcon() ?></td>
                    <td><?php echo e($row->itemInfo->name) ?></td>
                    <td><?php echo number_format($row->count, 0, '', '.') ?></td>
                    <td><?php echo $row->enchant ?></td>
                    <td><?php $this->widget('app.widgets.Status.Status', [
                            'status' => $row->status,
                            'statusText' => $row->getStatus()
                        ]) ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['form', 'bonus_id' => $row->bonus_id, 'item_id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['allow', 'bonus_id' => $row->bonus_id, 'item_id' => $row->id], ['class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['del', 'bonus_id' => $row->bonus_id, 'item_id' => $row->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
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

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]);
