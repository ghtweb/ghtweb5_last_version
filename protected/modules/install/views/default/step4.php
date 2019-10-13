<?php
/**
 * @var Step4Form $model
 * @var ActiveForm $form
 */

?>

<div class="page-header">
    <h1>Шаг 4, создание админа</h1>
</div>


<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ]
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'login', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'login', ['placeholder' => $model->getAttributeLabel('login'), 'class' => 'form-control']) ?>
        <p class="help-block">
            Разрешенные символы: <b><?php echo Users::LOGIN_REGEXP ?></b><br>
            Длина логина от <b><?php echo Users::LOGIN_MIN_LENGTH ?></b> до <b><?php echo Users::LOGIN_MAX_LENGTH ?></b> символов
        </p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'password', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->passwordField($model, 'password', ['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control']) ?>
        <p class="help-block">
            Длина пароля от <b><?php echo Users::PASSWORD_MIN_LENGTH ?></b> до <b><?php echo Users::PASSWORD_MAX_LENGTH ?></b> символов
        </p>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-primary">Завершить установку</button>
    </div>
</div>

<?php $this->endWidget() ?>
