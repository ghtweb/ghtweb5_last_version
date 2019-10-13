<?php
/**
 * @var \app\modules\cabinet\models\forms\ActivationBonusCodeForm $formModel
 * @var ActiveForm $form
 */

$title_ = 'Активация бонус кода';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => 'bonus-code-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ]
]) ?>

<?php echo $form->errorSummary($formModel) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group clearfix">
        <?php echo $form->labelEx($formModel, 'code', ['class' => 'col-lg-3 control-label']) ?>
        <div class="field">
            <?php echo $form->textField($formModel, 'code', ['placeholder' => $formModel->getAttributeLabel('code'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span>Активировать бонус код</span>
        </button>
    </div>

<?php $this->endWidget() ?>