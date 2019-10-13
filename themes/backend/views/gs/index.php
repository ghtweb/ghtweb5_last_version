<?php
/**
 * @var GameServersController $this
 * @var CActiveDataProvider $dataProvider
 * @var Gs[] $data
 * @var ActiveForm $form
 */

$title_ = 'Игровые сервера';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];

$serverVersion = serverVersionList();
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
            <col style="width: 10%;">
            <col style="width: 5%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 10%;">
            <col style="width: 12%;">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>IP</th>
            <th>Порт</th>
            <th>Версия</th>
            <th>Логин</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'name', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'ip', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->textField($model, 'port', ['class' => 'form-control input-sm']) ?></td>
            <td><?php echo $form->dropDownList($model, 'version', serverVersionList(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
            <td><?php echo $form->dropDownList($model, 'login_id', CHtml::listData(Ls::getOpenLoginServers(), 'id', 'name'), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
            <td><?php echo $form->dropDownList($model, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
            <td>
                <button type="submit" class="btn btn-primary glyphicon glyphicon-search btn-sm" title="Искать"></button>
                <?php echo CHtml::link('', ['index'], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle btn-sm', 'title' => 'Сбросить']) ?>
            </td>
        </tr>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo e($row->name) ?></td>
                    <td><?php echo $row->ip ?></td>
                    <td><?php echo $row->port ?></td>
                    <td><?php echo array_key_exists($row->version, $serverVersion) ? $serverVersion[$row->version] : 'n/a' ?></td>
                    <td><?php
                        if (is_object($row->ls)) {
                            echo CHtml::link(e($row->ls->name), ['/backend/loginServers/form', 'ls_id' => $row->ls->getPrimaryKey()]);
                        } else {
                            echo 'n/a';
                        }
                        ?></td>
                    <td><?php $this->widget('app.widgets.Status.Status', [
                            'status' => $row->status,
                            'statusText' => $row->getStatus()
                        ]) ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['/backend/gamesServerShopCategories/index', 'gs_id' => $row->id], ['class' => 'glyphicon glyphicon-shopping-cart', 'title' => 'Магазин', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['form', 'gs_id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['allow', 'gs_id' => $row->id], ['class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['del', 'gs_id' => $row->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8">Нет данных</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>