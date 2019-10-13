<?php
/**
 * @var UsersController $this
 * @var CActiveDataProvider $dataProvider
 * @var PurchaseItemsLog $model
 * @var PurchaseItemsLog[] $data
 * @var Users $user
 */

$title_ = 'Юзеры';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    $title_ => ['index'],
    $user->login . ' - Просмотр покупок в магазине',
];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col>
        <col style="width: 7%;">
        <col style="width: 7%;">
        <col style="width: 7%;">
        <col style="width: 7%;">
        <col style="width: 7%;">
        <col style="width: 15%;">
        <col style="width: 15%;">
    </colgroup>
    <thead>
    <tr>
        <th>ID</th>
        <th>Предмет</th>
        <th>Сервер</th>
        <th>Стоимость</th>
        <th>Скидка</th>
        <th>Кол-во</th>
        <th>Заточка</th>
        <th>ID персонажа на которого был перевод</th>
        <th>Дата</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($data = $dataProvider->getData()) { ?>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?php echo $row->id ?></td>
                <td><?php echo $row->itemInfo->name ?><?php echo($row->itemInfo->add_name ? '(' . $row->itemInfo->add_name . ')' : '') ?></td>
                <td><?php echo isset($row->gs->name) ? CHtml::link(e($row->gs->name), ['/backend/gameServers/form', 'gs_id' => $row->gs->getPrimaryKey()]) : 'n/a' ?></td>
                <td><?php echo $row->cost ?></td>
                <td><?php echo $row->discount ?></td>
                <td><?php echo $row->count ?></td>
                <td><?php echo $row->enchant ?></td>
                <td><?php echo $row->char_id ?></td>
                <td><?php echo $row->getCreatedAt() ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="9">Нет данных.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>
