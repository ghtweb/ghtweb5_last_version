<?php
/**
 * @var AdminsController $this
 * @var \app\modules\backend\models\forms\AdminCreateForm $model
 * @var ActiveForm $form
 */

$title__ = 'Админ сайта';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['index'],
    'Создание',
] ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'login', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'login', ['placeholder' => $model->getAttributeLabel('login'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'password', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'password', ['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">Создать</button>
        </div>
    </div>

<?php $this->endWidget() ?>