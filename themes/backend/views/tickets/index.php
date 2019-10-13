<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $dataProvider
 * @var Tickets[] $data
 * @var TicketsSearch $model
 * @var CActiveForm $form
 */

use app\modules\backend\models\search\TicketsSearch;

$title_ = 'Тикеты';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['index'],
]) ?>

    <table class="table table-striped">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 14%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Приоритет</th>
            <th>Статус</th>
            <th>Новые сообщения</th>
            <th>Сервер</th>
            <th>Автор</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'title', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->dropDownList($model, 'category_id', $model->getCategories(), ['class' => 'form-control input-sm', 'empty' => '-- select --']) ?></td>
            <td><?php echo $form->dropDownList($model, 'priority', $model->getPrioritiesList(), ['class' => 'form-control input-sm', 'empty' => '-- select --']) ?></td>
            <td><?php echo $form->dropDownList($model, 'status', $model->getStatusList(), ['class' => 'form-control input-sm', 'empty' => '-- select --', 'options' => ['empty' => ['selected' => 'selected']]]) ?></td>
            <td><?php echo $form->dropDownList($model, 'new_message_for_admin', [Tickets::STATUS_NEW_MESSAGE_OFF => 'Нет', Tickets::STATUS_NEW_MESSAGE_ON => 'Да'], ['class' => 'form-control input-sm', 'empty' => '-- select --']) ?></td>
            <td><?php echo $form->dropDownList($model, 'gs_id', $model->getGsList(), ['class' => 'form-control input-sm', 'empty' => '-- select --']) ?></td>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="Искать"
                        rel="tooltip"></button>
                <?php echo CHtml::link('', ['index'], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => 'Сбросить', 'rel' => 'tooltip']) ?>
            </td>
        </tr>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><?php echo $row->getPrimaryKey() ?></td>
                    <td><?php echo CHtml::encode($row->title) ?></td>
                    <td><?php echo CHtml::encode($model->getCategories()[$row->category_id]) ?></td>
                    <td><?php echo $row->getPriority() ?></td>
                    <td>
                        <span class="label <?php echo($row->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span>
                    </td>
                    <td>
                        <span class="label <?php echo($row->new_message_for_admin == Tickets::STATUS_NEW_MESSAGE_ON ? 'label-info' : 'label-default') ?>"><?php echo $row->isNewMessageForAdmin() ?></span>
                    </td>
                    <td><?php echo CHtml::link(CHtml::encode($model->getGsList()[$row->gs_id]), ['/backend/gameServers/form', 'gs_id' => $row->gs_id], ['target' => '_blank']) ?></td>
                    <td><?php echo(isset($row->user->login) ? CHtml::link($row->user->login, ['/backend/users/view', 'user_id' => $row->user->user_id], ['target' => '_blank']) : '*Unknown*') ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['edit', 'id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Просмотр', 'rel' => 'tooltip']) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9">Нет данных.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]);

