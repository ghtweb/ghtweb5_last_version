<?php
/**
 * @var UsersController $this
 * @var Users $user
 * @var UsersAuthLogs $model
 * @var CActiveDataProvider $dataProvider
 * @var UsersAuthLogs[] $data
 * @var ActiveForm $form
 */

$title_ = 'Юзеры';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    $title_ => ['index'],
    $user->login . ' - История авторизаций',
];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['/backend/users/authHistory', 'user_id' => $user->user_id],
]) ?>

<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col style="width: 15%;">
        <col>
        <col style="width: 10%;">
        <col style="width: 13%;">
        <col style="width: 10%;">
    </colgroup>
    <thead>
    <tr>
        <th>ID</th>
        <th>IP</th>
        <th>Браузер</th>
        <th>Доступ</th>
        <th>Дата</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
        <td><?php echo $form->textField($model, 'ip', ['class' => 'form-control input-sm']) ?></td>
        <td><?php echo $form->textField($model, 'user_agent', ['class' => 'form-control input-sm']) ?></td>
        <td><?php echo $form->dropDownList($model, 'status', $model->getStatusList(), ['class' => 'form-control input-sm']) ?></td>
        <td></td>
        <td>
            <button type="submit" class="btn btn-primary glyphicon glyphicon-search btn-sm" title="Искать"></button>
            <?php echo CHtml::link('', ['/backend/users/authHistory', 'user_id' => $user->user_id], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle btn-sm', 'title' => 'Сбросить']) ?>
        </td>
    </tr>
    <?php if ($data = $dataProvider->getData()) { ?>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?php echo $row->id ?></td>
                <td><?php echo CHtml::link($row->ip, getLocationLinkByIp($row->ip), ['title' => 'Информация о IP', 'rel' => 'tooltip', 'target' => '_blank']) ?></td>
                <td><?php echo e($row->user_agent) ?></td>
                <td>
                    <span class="label label-<?php echo $row->status == UsersAuthLogs::STATUS_AUTH_SUCCESS ? 'success' : 'warning' ?>">
                        <?php echo $row->getStatus() ?>
                    </span>
                </td>
                <td><?php echo $row->getCreatedAt('Y-m-d H:i') ?></td>
                <td></td>
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
