<?php

$title_ = 'Юзеры';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    $title_ => ['index'],
    $user->login . ' - ' . 'Рефералы',
];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col>
        <col style="width: 20%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
        <col style="width: 12%;">
    </colgroup>
    <thead>
    <tr>
        <th>ID</th>
        <th>Логин</th>
        <th>Email</th>
        <th>Баланс</th>
        <th>Дата создания</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($data = $dataProvider->getData()) { ?>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?php echo $row->id ?></td>
                <td><?php echo CHtml::link($row->referalInfo->login, ['/backend/users/view', 'user_id' => $row->referalInfo->user_id]) ?></td>
                <td><?php echo $row->referalInfo->email ?></td>
                <td><?php echo CHtml::link($row->referalInfo->profile->balance, ['transactionHistory', 'user_id' => $row->referalInfo->user_id], ['title' => 'История пополнений', 'rel' => 'tooltip']) ?></td>
                <td><?php echo $row->getCreatedAt('Y-m-d H:i') ?></td>
                <td>
                    <ul class="actions list-unstyled">
                         <li><?php echo CHtml::link('', ['view', 'user_id' => $row->referalInfo->user_id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Просмотр', 'rel' => 'tooltip']) ?></li>
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
