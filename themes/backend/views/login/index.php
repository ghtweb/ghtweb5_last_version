<?php
/**
 * @var BackendBaseController $this
 * @var LoginForm $model
 * @var ActiveForm $form
 */

?>

<?php $form = $this->beginWidget('ActiveForm', [
    'htmlOptions' => [
        'class' => 'form-signin',
        'id' => 'login-form',
    ],
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php echo $form->labelEx($model, 'login', ['class' => 'sr-only']) ?>
<?php echo $form->textField($model, 'login', ['placeholder' => 'Введите логин', 'class' => 'form-control input-lg input-login']) ?>

<?php echo $form->labelEx($model, 'password', ['class' => 'sr-only']) ?>
<?php echo $form->passwordField($model, 'password', ['placeholder' => 'Введите пароль', 'class' => 'form-control input-lg input-password']) ?>
<button type="submit" class="btn btn-success btn-lg btn-block" style="margin-top: 10px">
    Вход
</button>
<?php $this->endWidget() ?>
