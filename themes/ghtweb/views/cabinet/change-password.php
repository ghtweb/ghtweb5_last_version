<?php
/**
 * @var ChangePasswordController $this
 * @var ChangePasswordForm $model
 */

$title__ = 'Смена пароля от аккаунта';
$this->pageTitle = $title__;

$this->breadcrumbs = [$title__];
?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ]
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'old_password', ['class' => 'col-lg-3 control-label']) ?>
        <div class="field">
            <?php echo $form->passwordField($model, 'old_password', ['class' => 'form-control']) ?>
            <p class="help-block">От <?php echo Users::PASSWORD_MIN_LENGTH ?>
                до <?php echo Users::PASSWORD_MAX_LENGTH ?> символов</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'new_password', ['class' => 'col-lg-3 control-label']) ?>
        <div class="field">
            <?php echo $form->passwordField($model, 'new_password', ['class' => 'form-control']) ?>
            <p class="help-block">От <?php echo Users::PASSWORD_MIN_LENGTH ?>
                до <?php echo Users::PASSWORD_MAX_LENGTH ?> символов</p>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span>Изменить</span>
        </button>
    </div>

<?php $this->endWidget() ?>