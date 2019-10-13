<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $dataProvider
 * @var Tickets[] $data
 */

$title_ = 'Поддержка - список тикетов';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php echo CHtml::link('Создать тикет', ['/cabinet/tickets/add']) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <table class="table">
        <thead>
        <tr>
            <th>Номер</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Статус</th>
            <th>Дата создания</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $row) { ?>
                <tr<?php echo($row->new_message_for_user == Tickets::STATUS_NEW_MESSAGE_ON && $row->isStatusOn() ? ' class="new-message" title="Есть новое сообщение"' : '') ?>>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo CHtml::link($row->title, ['/cabinet/tickets/view', 'ticket_id' => $row->id]) ?></td>
                    <td><?php echo e($row->category->title) ?></td>
                    <td><?php echo $row->getStatus() ?></td>
                    <td><?php echo $row->getDate() ?></td>
                    <td>
                        <ul class="list-unstyled">
                            <?php if ($row->status == 1) { ?>
                                <li><?php echo CHtml::link('', ['/cabinet/tickets/close', 'ticket_id' => $row->id], ['class' => 'glyphicon glyphicon-eye-close', 'title' => 'Закрыть', 'rel' => 'tooltip']) ?></li>
                            <?php } ?>
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
]) ?>