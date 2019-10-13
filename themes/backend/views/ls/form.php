<?php
/**
 * @var LoginServersController $this
 * @var LsForm $model
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\LsForm;

$title__ = 'Логин сервера';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['index'],
    ($model->isNewRecord ? 'Редактирование' : 'Добавление'),
];
?>


<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <legend>Разное</legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'name', ['placeholder' => $model->getAttributeLabel('name'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'ip', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'ip', ['placeholder' => $model->getAttributeLabel('ip'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'port', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'port', ['placeholder' => $model->getAttributeLabel('port'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'version', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'version', serverVersionList(), ['class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', $model->getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'password_type', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'password_type', Ls::getPasswordTypeList(), ['class' => 'form-control']) ?>
            <p class="help-block">Тип шифрования пароля</p>
        </div>
    </div>

    <legend>Подключение к базе данных</legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_host', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_host', ['placeholder' => $model->getAttributeLabel('db_host'), 'class' => 'form-control']) ?>
            <p class="help-block">IP адрес БД сервера.</p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_port', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_port', ['placeholder' => $model->getAttributeLabel('db_port'), 'class' => 'form-control']) ?>
            <p class="help-block">Порт от БД сервера.</p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_user', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_user', ['placeholder' => $model->getAttributeLabel('db_user'), 'class' => 'form-control']) ?>
            <p class="help-block">Пользователь от БД сервера.</p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_pass', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'db_pass', ['placeholder' => $model->getAttributeLabel('db_pass'), 'class' => 'form-control']) ?>
            <p class="help-block">Пароль от БД сервера.</p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_name', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_name', ['placeholder' => $model->getAttributeLabel('db_name'), 'class' => 'form-control']) ?>
            <p class="help-block">Название БД сервера.</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit"
                    class="btn btn-primary"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>