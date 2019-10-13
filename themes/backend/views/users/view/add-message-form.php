<?php
/**
 * @var UsersController $this
 * @var UserMessages $model
 * @var ActiveForm $form
 */

?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => 'add-bonus-form',
    'htmlOptions' => [
        'class' => 'form-horizontal js-add-message-form',
    ],
]) ?>

    <p class="help-block"><span class="required">*</span> После отправки сообщения, юзер его увидит при первом посещении
        сайта, обратный ответ он написать не сможет.</p>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'message', ['class' => 'col-sm-3 control-label']) ?>
        <div class="col-sm-9">
            <?php echo $form->textArea($model, 'message', ['placeholder' => $model->getAttributeLabel('message'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </div>

<?php $this->endWidget() ?>