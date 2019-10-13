<?php

$title_ = 'Персонажи';
$this->pageTitle = $title_;

$this->breadcrumbs = [$title_];
?>


<?php if (is_string($error)) { ?>
    <div class="alert alert-danger">
        <?php echo $error ?>
    </div>
<?php } ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php if ($error === false) { ?>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Клан</th>
            <th>Статус</th>
            <th>Время в игре</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($characters) { ?>
            <?php foreach ($characters as $i => $char) { ?>
                <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                    <td><?php echo ++$i ?></td>
                    <td><?php echo e($char['char_name']) ?><br>
                        <p class="class-name"
                           style="margin: 0;"><?php echo Lineage::getClassName($char['base_class']) ?>
                            <span>(<?php echo $char['level'] ?>)</span></p></td>
                    <td><?php echo($char['clan_name'] ? e($char['clan_name']) : '--') ?></td>
                    <td><?php echo($char['online'] ? '<span style="color: green;">В игре</span>' : '<span style="color: red;">Не в игре</span>') ?></td>
                    <td><?php echo Lineage::getOnlineTime($char['onlinetime']) ?></td>
                    <td>
                        <ul class="actions">
                            <li><?php echo CHtml::link('', ['/cabinet/characters/view', 'char_id' => $char['char_id']], ['class' => 'glyphicon glyphicon-eye-open', 'title' => 'Просмотр', 'rel' => 'tooltip']) ?></li>
                            <?php if ($char['online'] == 0) { ?>
                                <li><?php echo CHtml::link('', ['/cabinet/characters/teleport', 'char_id' => $char['char_id']], ['class' => 'glyphicon glyphicon-cloud-upload', 'title' => 'Телепорт в город', 'rel' => 'tooltip']) ?></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">Нет персонажей.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php } ?>