<?php
/**
 * @var ForgottenPasswordForm $model
 * @var ActiveForm $form
 */

$title_ = 'Восстановление пароля от аккаунта';
$this->pageTitle = $title_;
?>

<div class="inner">
    <h2><?php echo e($title_) ?></h2>

    <?php $form = $this->beginWidget('ActiveForm', [
        'id' => 'forgotten-password-form',
        'htmlOptions' => [
            'class' => 'form-horizontal',
        ],
    ]) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <?php if (count($model->gs_list) > 1) { ?>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'gs_id') ?>
            <div class="field">
                <?php echo $form->dropDownList($model, 'gs_id', CHtml::listData($model->gs_list, 'id', 'name'), ['class' => 'form-control']) ?>
            </div>
        </div>
    <?php } ?>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'login') ?>
        <div class="field">
            <?php echo $form->textField($model, 'login', ['placeholder' => $model->getAttributeLabel('login'), 'class' => 'form-control']) ?>
            <p class="help-block">
                Длина должна быть от <?php echo Users::LOGIN_MIN_LENGTH ?> до <?php echo Users::LOGIN_MAX_LENGTH ?>
                символов
                <br>
            </p>
        </div>
    </div>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'email') ?>
        <div class="field">
            <?php echo $form->textField($model, 'email', ['placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <?php if (CCaptcha::checkRequirements() && config('forgotten_password.captcha.allow')) { ?>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'verifyCode') ?>
            <div class="field captcha">
                <?php echo $form->textField($model, 'verifyCode', ['placeholder' => $model->getAttributeLabel('verifyCode'), 'class' => 'form-control']) ?>
                <div class="captcha-image">
                    <?php $this->widget('CCaptcha', [
                        'id' => 'forgotten-password-form-captcha'
                    ]) ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="button-group center">
        <button type="submit" class="button">
            <span>Восстановить</span>
        </button>
    </div>

    <?php $this->endWidget() ?>
</div>