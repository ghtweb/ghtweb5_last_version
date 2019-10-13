<?php
/**
 * @var DepositController $this
 * @var DepositForm $model
 * @var Deposit $deposit
 * @var ActiveForm $form
 */

//$assetsUrl = assetsUrl();

$title_ = 'Пополнение баланса';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php if ($this->gs->deposit_allow) { ?>
    <?php $form = $this->beginWidget('ActiveForm', [
        'id' => 'cabinet-deposit-form',
        'htmlOptions' => [
            'class' => 'form-horizontal',
        ]
    ]) ?>

    <div class="alert alert-info">
        <p>Итоговая стоимость может незначительно отличаться в зависимости от способа оплаты.</p>
    </div>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'sum', ['class' => 'col-lg-3 control-label']) ?>
        <div class="field">
            <?php echo $form->textField($model, 'sum', ['class' => 'form-control']) ?>
            <p class="help-block">Стоимость 1 <?php echo $this->gs->currency_name ?> = <?php echo $this->gs->deposit_course_payments ?> <?php echo $this->gs->getCurrencySymbolName() ?></p>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span>Далее</span>
        </button>
    </div>

    <?php $this->endWidget() ?>
<?php } else { ?>
    <div class="alert alert-info">Пополнение баланса отключено.</div>
<?php } ?>
