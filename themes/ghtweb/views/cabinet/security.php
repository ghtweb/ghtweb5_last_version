<?php
/**
 * @var SecurityController $this
 * @var UserProfiles $model
 */

$title__ = 'Безопасность';
$this->pageTitle = $title__;

$this->breadcrumbs = [$title__];
?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => 'security-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ]
]) ?>

    <div class="hint">Ваш текущий IP адрес: <b><?php echo userIp() ?></b></div>

    <div class="alert alert-info">
        Вы можете привязать Ваш аккаунт на сайте к определенному IP адресу или нескольким IP адресам.
        <br>
        <span class="required">*</span> Пустое поле отключает привязку к IP
    </div>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'protected_ip') ?>
        <div class="field">
            <?php echo $form->textArea($model, 'protected_ip', ['class' => 'form-control']) ?>
            <p class="help-block">Если хотите ввести несколько IP адресов то каждый IP должен быть с новой строки.</p>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span>Сохранить</span>
        </button>
    </div>

<?php $this->endWidget() ?>