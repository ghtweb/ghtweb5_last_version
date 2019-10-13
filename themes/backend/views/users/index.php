<?php
/**
 * @var UsersController $this
 * @var ActiveForm $form
 * @var CActiveDataProvider $dataProvider
 * @var Users[] $data
 * @var UsersSearch $model
 * @var Ls[] $lsList
 */

use app\modules\backend\models\search\UsersSearch;

$title_ = 'Юзеры';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['index'],
]) ?>

    <table class="table table-striped">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 20%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 15%;">
            <col style="width: 12%;">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Email</th>
            <th>Баланс</th>
            <th>Рефералов</th>
            <th>Роль</th>
            <th>Логин сервер</th>
            <th>Зарегистрирован</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $form->textField($model, 'user_id', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'login', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'email', ['class' => 'form-control input-sm']) ?></td>
            <td></td>
            <td></td>
            <td><?php echo $form->dropDownList($model, 'role', $model->getRoleList(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
            <td><?php echo $form->dropDownList($model, 'ls_id', $model->getLsList(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary glyphicon glyphicon-search btn-sm" title="Искать" rel="tooltip"></button>
                <?php echo CHtml::link('', ['index'], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle btn-sm', 'title' => 'Сбросить', 'rel' => 'tooltip']) ?>
            </td>
        </tr>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $i => $row) { ?>
                <tr>
                    <td><?php echo $row->user_id ?></td>
                    <td><?php echo CHtml::link(CHtml::encode($row->login), ['view', 'user_id' => $row->getPrimaryKey()]) ?></td>
                    <td><?php echo $row->email ?></td>
                    <td><?php echo CHtml::link(formatCurrency($row->profile->balance, false), ['transactionHistory', 'user_id' => $row->user_id], ['title' => 'История пополнений', 'rel' => 'tooltip']) ?></td>
                    <td><?php echo CHtml::link(count($row->referals), ['referals', 'user_id' => $row->user_id], ['title' => 'Список рефералов', 'rel' => 'tooltip']) ?></td>
                    <td>
                        <?php
                        $roleClassName = 'default';
                        if ($row->role == Users::ROLE_BANNED) {
                            $roleClassName = 'danger';
                        }
                        ?>
                        <span class="label label-<?php echo $roleClassName ?>">
                            <?php echo $row->getRole() ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row->ls) { ?>
                            <?php echo CHtml::link(e($row->ls->name), ['/backend/loginServers/form', 'ls_id' => $row->ls->getPrimaryKey()], ['title' => 'Просмотр сервера', 'rel' => 'tooltip']) ?>
                        <?php } else { ?>
                            n/a
                        <?php } ?>
                    </td>
                    <td><?php echo $row->getCreatedAt('Y-m-d H:i') ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-default btn-sm dropdown-toggle glyphicon glyphicon-cog" type="button"
                                    id="dropdownMenu<?php echo $i ?>" data-toggle="dropdown"></button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu<?php echo $i ?>">
                                <li><?php echo CHtml::link('Просмотр', ['view', 'user_id' => $row->getPrimaryKey()]) ?></li>
                                <li><?php echo CHtml::link('История авторизаций', ['authHistory', 'user_id' => $row->getPrimaryKey()]) ?></li>
                                <li><?php echo CHtml::link('История покупок в магазине', ['itemPurchaseLog', 'user_id' => $row->getPrimaryKey()]) ?></li>
                                <li><?php echo CHtml::link('История пополнений баланса', ['transactionHistory', 'user_id' => $row->getPrimaryKey()]) ?></li>
                                <li><?php echo CHtml::link('Игроки которых привел', ['referals', 'user_id' => $row->getPrimaryKey()]) ?></li>
                            </ul>
                        </div>
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
