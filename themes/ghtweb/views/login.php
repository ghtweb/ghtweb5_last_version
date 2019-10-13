<?php
/**
 * @var int $failedAttempt
 * @var ActiveForm $form
 * @var LoginForm $model
 */

$title_ = 'Авторизация';
$this->pageTitle = $title_;
?>

<div class="inner">
    <h2 class="title register"><?php echo e($title_) ?></h2>

    <?php if ($model->isBlockedForm()) { ?>

        <div class="alert alert-info">
            <?php

            $min = Yii::t('main', '{n} минуту|{n} минуты|{n} минут|{n} минуты', (int)config('login.failed_attempts_blocked_time')) ?>
            Вы заблокированы на <?php echo $min ?>.
        </div>

    <?php } elseif (!$model->getGsList()) { ?>
        <div class="alert alert-info">
            Авторизация невозможна из за отсутствия серверов.
        </div>
    <?php } else { ?>

        <?php $form = $this->beginWidget('ActiveForm', [
            'id' => 'login-form',
            'htmlOptions' => [
                'class' => 'form-horizontal',
            ],
        ]) ?>

        <?php if ($model->getCountBadAttempt() > 0) { ?>
            <div class="alert alert-info">
                <h4>Внимание!</h4>
                <?php

                $cbe = Yii::t('main', '{n} неудачную попытку|{n} неудачные попытки|{n} неудачных попыток|{n} неудачные попытки', config('login.count_failed_attempts_for_blocked') - $model->getCountBadAttempt());
                $min = Yii::t('main', '{n} минуту|{n} минуты|{n} минут|{n} минуты', config('login.failed_attempts_blocked_time')) ?>
                Через <?php echo $cbe ?> авторизации Вы будете заблокированы на <?php echo $min ?>
            </div>
        <?php } ?>

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
                <p class="help-block">Длина должна быть от <?php echo Users::LOGIN_MIN_LENGTH ?>
                    до <?php echo Users::LOGIN_MAX_LENGTH ?> символов
                    <br>
                </p>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'password') ?>
            <div class="field">
                <?php echo $form->passwordField($model, 'password', ['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control']) ?>
                <p class="help-block">Длина должна быть от <?php echo Users::PASSWORD_MIN_LENGTH ?>
                    до <?php echo Users::PASSWORD_MAX_LENGTH ?> символов
                    <br>
                </p>
            </div>
        </div>

        <?php if (CCaptcha::checkRequirements() && (bool)config('login.captcha.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'verifyCode') ?>
                <div class="field captcha">
                    <?php echo $form->textField($model, 'verifyCode', ['placeholder' => $model->getAttributeLabel('verifyCode'), 'class' => 'form-control']) ?>
                    <div class="captcha-image">
                        <?php $this->widget('CCaptcha', [
                            'id' => 'login-form-captcha'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="button-group center">
            <button type="submit" class="button">
                <span>Войти</span>
            </button>
            <?php echo CHtml::link('Забыли пароль?', ['/forgottenPassword/default/index']) ?>
        </div>

        <?php $this->endWidget() ?>

    <?php } ?>

</div>