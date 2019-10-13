<?php
/**
 * @var ActiveForm $form
 * @var Step2Form $model
 */

?>

<div class="page-header">
    <h1>Шаг 2, настройка подключения к БД сайта</h1>
</div>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ]
]) ?>

<?php echo nl2br($form->errorSummary($model)) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'mysql_host', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'mysql_host', ['placeholder' => $model->getAttributeLabel('mysql_host'), 'class' => 'form-control']) ?>
        <p class="help-block">Default: 127.0.0.1</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'mysql_port', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'mysql_port', ['placeholder' => $model->getAttributeLabel('mysql_port'), 'class' => 'form-control']) ?>
        <p class="help-block">Default: 3306</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'mysql_user', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'mysql_user', ['placeholder' => $model->getAttributeLabel('mysql_user'), 'class' => 'form-control']) ?>
        <p class="help-block">Default: root</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'mysql_pass', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->passwordField($model, 'mysql_pass', ['placeholder' => $model->getAttributeLabel('mysql_pass'), 'class' => 'form-control']) ?>
        <p class="help-block">Разрешены все символы кроме <b>' \</b></p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'mysql_name', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'mysql_name', ['placeholder' => $model->getAttributeLabel('mysql_name'), 'class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-primary">Далее</button>
    </div>
</div>

<?php $this->endWidget() ?>

