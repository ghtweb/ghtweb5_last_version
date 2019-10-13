<?php
/**
 * @var TicketsController $this
 * @var TicketsForm $formModel
 * @var Tickets $ticketModel
 * @var ActiveForm $form
 */

use app\modules\cabinet\models\forms\TicketsForm;

$title_ = 'Поддержка - создание тикета';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    'Поддержка' => ['index'],
    'Создание тикета'
] ?>


<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ]
]) ?>

<?php echo $form->errorSummary($formModel) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<div class="form-group clearfix">
    <?php echo $form->labelEx($formModel, 'category_id', ['class' => 'col-lg-3 control-label']) ?>
    <div class="field">
        <?php echo $form->dropDownList($formModel, 'category_id', $formModel->getCategories(), ['class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group clearfix">
    <?php echo $form->labelEx($formModel, 'priority', ['class' => 'col-lg-3 control-label']) ?>
    <div class="field">
        <?php echo $form->dropDownList($formModel, 'priority', $ticketModel->getPrioritiesList(), ['class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group clearfix">
    <?php echo $form->labelEx($formModel, 'date_incident', ['class' => 'col-lg-3 control-label']) ?>
    <div class="field">
        <?php echo $form->textField($formModel, 'date_incident', ['placeholder' => $formModel->getAttributeLabel('date_incident'), 'class' => 'form-control']) ?>
        <p class="help-block">К примеру: <?php echo date('Y-m-d H:i') ?></p>
    </div>
</div>
<div class="form-group clearfix">
    <?php echo $form->labelEx($formModel, 'char_name', ['class' => 'col-lg-3 control-label']) ?>
    <div class="field">
        <?php echo $form->textField($formModel, 'char_name', ['placeholder' => $formModel->getAttributeLabel('char_name'), 'class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group clearfix">
    <?php echo $form->labelEx($formModel, 'title', ['class' => 'col-lg-3 control-label']) ?>
    <div class="field">
        <?php echo $form->textField($formModel, 'title', ['placeholder' => $formModel->getAttributeLabel('title'), 'class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group clearfix">
    <?php echo $form->labelEx($formModel, 'text', ['class' => 'col-lg-3 control-label']) ?>
    <div class="field">
        <?php echo $form->textArea($formModel, 'text', ['placeholder' => $formModel->getAttributeLabel('text'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="button-group center">
    <button type="submit" class="button">
        <span>Создать</span>
    </button>
</div>

<?php $this->endWidget() ?>
