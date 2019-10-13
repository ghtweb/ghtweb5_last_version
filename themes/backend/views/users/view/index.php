<?php
/**
 * @var UsersController $this
 * @var Users $model
 * @var UserBonuses $bonus
 */

js(app()->getThemeManager()->getBaseUrl() . '/js/users_view.js', CClientScript::POS_END);

$title_ = 'Юзеры';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    $title_ => ['index'],
    $model->login . ' - ' . 'Просмотр',
] ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<style>
    .panel h4 {
        margin: 0;
    }
    .panel-items .table {
        margin-bottom: 0;
    }
</style>

<div class="panel panel-info">
    <div class="panel-heading"><h4>Данные юзера</h4></div>
    <div class="panel-body">
        <table class="table table-striped table-bordered">
            <colgroup>
                <col style="width: 25%;">
                <col style="width: 25%;">
                <col style="width: 25%;">
                <col style="width: 25%;">
            </colgroup>
            <tbody>
            <tr>
                <td><b>Логин</b></td>
                <td><?php echo $model->login ?></td>
                <td><b>Email</b></td>
                <td><?php echo $model->email ?></td>
            </tr>
            <tr>
                <td><b>Статус</b></td>
                <td><?php echo $model->getActivatedStatus() ?></td>
                <td><b>Реферальный код</b></td>
                <td><?php echo $model->referer ?></td>
            </tr>
            <tr>
                <td><b>Роль</b></td>
                <td><?php echo $model->getRole() ?></td>
                <td><b>Баланс</b></td>
                <td><?php echo $model->profile->balance ?></td>
            </tr>
            <tr>
                <td><b>Привязка к IP</b></td>
                <td><?php echo($model->profile->protected_ip && is_array($model->profile->protected_ip) ? implode(', ', $model->profile->protected_ip) : 'Привязки к IP нет') ?></td>
                <td><b></b></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <?php echo CHtml::link('Добавить бонус', ['addBonus', 'user_id' => $model->getPrimaryKey()], ['class' => 'btn btn-xs btn-primary js-add-bonus']) ?>
        &nbsp;
        <?php echo CHtml::link('Отправить сообщение', ['addMessage', 'user_id' => $model->getPrimaryKey()], ['class' => 'btn btn-xs btn-primary js-send-message']) ?>
        &nbsp;
        <?php echo CHtml::link('Редактировать данные юзера', ['editData', 'user_id' => $model->getPrimaryKey()], ['class' => 'btn btn-xs btn-primary js-edit-data']) ?>

    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading"><h4>Бонусы</h4></div>
    <div class="panel-body">
        <?php if ($model->bonuses) { ?>
            <div class="row">
                <?php foreach (array_chunk($model->bonuses, round(count($model->bonuses) / 2)) as $bonuses) { ?>
                    <div class="col-md-6">
                        <?php foreach ($bonuses as $bonus) { ?>
                            <div class="panel panel-default panel-items">
                                <div class="panel-heading">
                                    <?php echo $bonus->bonusInfo->title ?>
                                    <span class="label label-<?php echo $bonus->isActivated() ? 'success' : 'default' ?>"><?php echo $bonus->getState() ?></span>
                                    <?php if (!$bonus->isActivated()) { ?>
                                        <?php echo CHtml::link('', ['delBonus', 'user_id' => $model->user_id, 'bonus_id' => $bonus->id], [
                                            'class' => 'glyphicon glyphicon-remove js-confirm-del',
                                            'rel' => 'tooltip',
                                            'title' => 'Удалить бонус у юзера',
                                            'style' => 'display:inline-block;vertical-align:middle;'
                                        ]) ?>
                                    <?php } else { ?>
                                        <span class="label label-default">
                                            на персонажа - <?php echo $bonus->char_name ?>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="panel-body">
                                    <?php if ($items = $bonus->bonusInfo->items) { ?>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th width="5%"></th>
                                            <th>Предмет</th>
                                            <th width="15%">Кол-во</th>
                                            <th width="10%">Заточка</th>
                                            <th width="10%">Статус</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($items as $item) { ?>
                                            <tr>
                                                <td><?php echo $item->itemInfo->getIcon() ?></td>
                                                <td><?php echo $item->itemInfo->name ?></td>
                                                <td><?php echo number_format($item->count, 0, '', '.') ?></td>
                                                <td><?php echo $item->enchant ?></td>
                                                <td>
                                                    <span class="label label-<?php echo $item->isStatusOn() ? 'success' : 'default' ?>"><?php echo $item->getStatus() ?></span>
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
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-info">
                Нет данных.
            </div>
        <?php } ?>
    </div>
</div>



