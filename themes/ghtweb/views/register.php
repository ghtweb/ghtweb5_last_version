<?php
/**
 * @var Controller $this
 * @var RegisterForm $model
 * @var ActiveForm $form
 */

$title_ = 'Регистрация';
$this->pageTitle = $title_;
?>

<?php if($downloadToken = user()->getFlash('download-token')) { ?>
    <script>
        setTimeout(function(){
            window.location.href = "<?php echo app()->createUrl('register/default/downloadRegisterData', ['token' => $downloadToken]) ?>";
        }, 1000);
    </script>
<?php } ?>

<div class="inner">
    <h2 class="title register"><?php echo e($title_) ?></h2>

    <?php if (!$model->gs_list) { ?>
        <div class="alert alert-info">
            Регистрация невозможна из за отсутствия серверов.
        </div>
    <?php } else { ?>
        <?php $form = $this->beginWidget('ActiveForm', [
            'id' => 'register-form',
            'htmlOptions' => [
                'class' => 'form-horizontal',
            ]
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

        <?php if (config('prefixes.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'prefix') ?>
                <div class="field">
                    <?php echo $form->dropDownList($model, 'prefix', $model->getPrefixes(), ['class' => 'form-control']) ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'login') ?>
            <div class="field">
                <?php echo $form->textField($model, 'login', ['placeholder' => $model->getAttributeLabel('login'), 'class' => 'form-control']) ?>
                <p class="help-block">Разрешенные символы: <?php echo Users::LOGIN_REGEXP ?><br>
                    Длина должна быть от <?php echo Users::LOGIN_MIN_LENGTH ?> до <?php echo Users::LOGIN_MAX_LENGTH ?>
                    символов
                    <br>
                </p>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'password') ?>
            <div class="field">
                <?php echo $form->passwordField($model, 'password', ['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control', 'style' => 'position: inherit;']) ?>
                <p class="help-block">Длина должна быть от <?php echo Users::PASSWORD_MIN_LENGTH ?>
                    до <?php echo Users::PASSWORD_MAX_LENGTH ?> символов
                    <br>
                </p>
                <?php $this->widget('app.widgets.PasswordGenerator.PasswordGenerator', [
                    'text' => 'сгенерировать пароль', // Название кнопки
                    'minLength' => Users::PASSWORD_MIN_LENGTH, // Мин. длина пароля
                    'maxLength' => Users::PASSWORD_MAX_LENGTH, // Макс. длина пароля
                ]) ?>

            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 're_password') ?>
            <div class="field">
                <?php echo $form->passwordField($model, 're_password', ['placeholder' => $model->getAttributeLabel('re_password'), 'class' => 'form-control']) ?>
                <p class="help-block">Длина должна быть от <?php echo Users::PASSWORD_MIN_LENGTH ?>
                    до <?php echo Users::PASSWORD_MAX_LENGTH ?> символов
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

        <?php if (config('referral_program.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'referer') ?>
                <div class="field">
                    <?php echo $form->textField($model, 'referer', ['placeholder' => $model->getAttributeLabel('referer'), 'class' => 'form-control']) ?>
                </div>
            </div>
        <?php } ?>

        <?php if (CCaptcha::checkRequirements() && config('register.captcha.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'verifyCode') ?>
                <div class="field captcha">
                    <?php echo $form->textField($model, 'verifyCode', ['placeholder' => $model->getAttributeLabel('verifyCode'), 'class' => 'form-control']) ?>
                    <div class="captcha-image">
                        <?php $this->widget('CCaptcha', [
                            'id' => 'register-form-captcha'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="button-group center">
            <button type="submit" class="button">
                <span>Зарегистрироваться</span>
            </button>
        </div>

        <?php $this->endWidget() ?>
    <?php } ?>

</div>


