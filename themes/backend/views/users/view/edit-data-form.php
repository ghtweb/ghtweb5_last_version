<?php
/**
 * @var UsersController $this
 * @var EditUserForm $formModel
 * @var Users $userModel
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\EditUserForm;

?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => 'edit-data-form',
    'htmlOptions' => [
        'class' => 'form-horizontal js-edit-data-form',
    ],
]) ?>

    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'role', ['class' => 'col-sm-3 control-label']) ?>
        <div class="col-sm-9">
            <?php echo $form->dropDownList($formModel, 'role', EditUserForm::getRoleList(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'activated', ['class' => 'col-sm-3 control-label']) ?>
        <div class="col-sm-9">
            <?php echo $form->dropDownList($formModel, 'activated', EditUserForm::getActivatedStatusList(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'balance', ['class' => 'col-sm-3 control-label']) ?>
        <div class="col-sm-9">
            <?php echo $form->textField($formModel, 'balance', ['placeholder' => $formModel->getAttributeLabel('balance'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'phone', ['class' => 'col-sm-3 control-label']) ?>
        <div class="col-sm-9">
            <?php echo $form->textField($formModel, 'phone', ['placeholder' => $formModel->getAttributeLabel('phone'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'protected_ip', ['class' => 'col-sm-3 control-label']) ?>
        <div class="col-sm-9">
            <?php echo $form->textArea($formModel, 'protected_ip', ['placeholder' => $formModel->getAttributeLabel('protected_ip'), 'class' => 'form-control']) ?>
            <p class="help-block">Привязка к IP адресу(ам), новый IP с новой строки</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>

<?php $this->endWidget() ?>