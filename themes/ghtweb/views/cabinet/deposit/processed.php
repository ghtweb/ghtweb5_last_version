<?php
/**
 * @var DepositController $this
 * @var Transactions $model
 * @var string $formAction
 * @var string $fields
 * @var Deposit $deposit
 */

$title_ = 'Подтверждение платежа';
$this->pageTitle = $title_;

$this->breadcrumbs = [
    'Пополнение баланса' => ['/cabinet/deposit/index'],
    $title_
];
?>

<table class="table table-striped">
    <colgroup>
        <col style="width: 40%;">
        <col style="width: 60%;">
    </colgroup>
    <tbody>
    <tr>
        <td>Платежная система</td>
        <td><?php echo $deposit->getAggregatorName() ?></td>
    </tr>
    <tr class="even">
        <td>Номер заявки</td>
        <td><?php echo $model->id ?></td>
    </tr>
    <tr>
        <td>Получаете</td>
        <td><?php echo $model->count . ' ' . $this->gs->currency_name ?></td>
    </tr>
    <tr class="even">
        <td>Отдаете</td>
        <td><?php echo app()->numberFormatter->formatCurrency($model->sum, $this->gs->currency_symbol) ?></td>
    </tr>
    </tbody>
</table>

<form action="<?php echo $formAction ?>" method="post">
    <?php echo $fields ?>
    <div class="button-group center">
        <button type="submit" class="button">
            <span>Перейти к оплате</span>
        </button>
        <?php echo CHtml::link('назад', ['/cabinet/deposit/index']) ?>
    </div>
</form>