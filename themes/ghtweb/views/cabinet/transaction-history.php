<?php
/**
 * @var TransactionHistoryController $this
 * @var CActiveDataProvider $dataProvider
 * @var Transactions[] $data
 */

$title_ = 'История пополнений';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

    <div class="entry">
        <div class="scroll-pane">
            <table class="table">
                <colgroup>
                    <col style="width: 10%;">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col style="width: 25%;">
                </colgroup>
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?php echo $this->gs->currency_name ?></th>
                    <th>Стоимость</th>
                    <th>Статус</th>
                    <th>Тип</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($data = $dataProvider->getData()) { ?>
                    <?php foreach ($data as $i => $transaction) { ?>
                        <tr>
                            <td><?php echo $transaction->getPrimaryKey() ?></td>
                            <td><?php echo $transaction->count ?></td>
                            <td><?php echo $transaction->sum ?></td>
                            <td><?php echo $transaction->status
                                    ? '<span style="color: green;">Завершена</span>'
                                    : '<span style="color: red;">Не завершена</span>' ?></td>
                            <td><?php echo $transaction->getType() ?></td>
                            <td><?php echo $transaction->getCreatedAt('Y-m-d H:i') ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6">Нет данных.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>