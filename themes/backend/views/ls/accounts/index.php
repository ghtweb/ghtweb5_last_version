<?php
/**
 * @var LoginServersController $this
 * @var CArrayDataProvider $dataProvider
 * @var array $data
 */

$title_ = 'Логин сервера';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    $title_ => ['/backend/loginServers/index'],
    $ls->name . ' - ' . 'аккаунты',
];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['/backend/' . $this->getId() . '/index'],
]) ?>

    <table class="table">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 5%;">
        </colgroup>
        <thead>
        <tr>
            <th>#</th>
            <th>Login</th>
            <th>Last Active</th>
            <th>Access Level</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data = $dataProvider->getData()) { ?>
            <?php foreach ($data as $i => $row) { ?>
                <tr>
                    <td><?php echo getNumberForPagination(++$i, $perPage) ?></td>
                    <td><?php echo $row['login'] ?></td>
                    <td><?php echo($row['last_active'] ? date('Y-m-d H:i', $row['last_active']) : '-') ?></td>
                    <td><?php echo $row['access_level'] ?></td>
                    <td>
                        <!-- <ul class="actions list-unstyled">
                                <li><?php echo CHtml::link('', ['/backend/' . $this->getId() . '/edit', 'ls_id' => $ls->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            </ul> -->
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

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>