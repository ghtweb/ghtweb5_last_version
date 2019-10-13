<?php
/**
 * @var BonusesForm $model
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\BonusesForm;

js(app()->getThemeManager()->getBaseUrl() . '/js/bonuses_codes_add.js', CClientScript::POS_END);

$title__ = 'Бонус - коды';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['index'],
    ($model->isNewRecord ? 'Добавление' : 'Редактирование'),
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
    <?php echo $form->labelEx($model, 'code', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'code', ['placeholder' => $model->getAttributeLabel('code'), 'class' => 'form-control']) ?>
        <p class="help-block">К примеру: 1111-MP09-SREW-MP5S <a
                    href="<?php echo $this->createUrl('/backend/bonusCodes/generateCode') ?>"
                    class="js-generate-new-bonus-code">Сгенерировать код</a></p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'bonus_id', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'bonus_id', CHtml::listData(Bonuses::model()->findAll(), 'id', 'title'), ['class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'limit', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'limit', ['placeholder' => $model->getAttributeLabel('limit'), 'class' => 'form-control']) ?>
        <p class="help-block">Сколько раз можно активировать этот код пользователю</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'status', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        <button type="submit"
                class="btn btn-primary"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить') ?></button>
    </div>
</div>

<?php $this->endWidget() ?>
