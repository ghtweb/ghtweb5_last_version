<?php
/**
 * @var CActiveDataProvider $dataProvider
 * @var UserMessages[] $data
 */

$title_ = 'Личные сообщения';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<div class="entry">
    <div class="scroll-pane">
        <table class="table">
            <colgroup>
                <col style="width: 5%;">
                <col>
                <col style="width: 10%;">
                <col style="width: 25%;">
            </colgroup>
            <thead>
            <tr>
                <th>#</th>
                <th>Сообщение</th>
                <th>Новое</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($data = $dataProvider->getData()) { ?>
                <?php foreach ($data as $i => $row) { ?>
                    <tr>
                        <td><?php echo ++$i ?></td>
                        <td><?php echo CHtml::link($row->getShortMessage(5), ['detail', 'id' => $row->getPrimaryKey()]) ?></td>
                        <td><?php echo($row->read == UserMessages::STATUS_NOT_READ ? 'Да' : 'Нет') ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row->created_at)) ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">Нет сообщений</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php $this->widget('CLinkPager', [
            'pages' => $dataProvider->getPagination(),
        ]) ?>
    </div>
</div>
