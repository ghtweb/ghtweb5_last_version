<?php
/**
 * @var BonusesController $this
 * @var Bonuses $bonusModel
 * @var BonusesItemsForm $model
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\BonusesItemsForm;

js(app()->getThemeManager()->getBaseUrl() . '/js/bonuses_items.js', CClientScript::POS_END);

$title__ = 'Бонусы';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['/backend/bonuses/index'],
    $bonusModel->title => ['/backend/bonusesItems/index', 'bonus_id' => $bonusModel->id],
    ($model->isNewRecord ? 'Добавление предмета' : 'Редактирование предмета'),
];
?>

    <div class="js-urlItemInfo hidden"><?php echo $this->createUrl('/backend/default/getItemInfo') ?></div>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <style>
        .img-block {
            position: relative;
        }

        .img-block .img {
            position: absolute;
            top: 0;
            right: -20px;
            width: 32px;
            height: 32px;
        }
    </style>

    <p class="help-block" style="font-size: 13px;">
        <b style="color: red;">*</b> Чтобы добавить предмет достаточно начать набирать его название или ввести его ID
    </p>
    <br>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'item_name', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9 img-block">
            <div class="img js-item-icon">
                <?php if ($model->isNewRecord === false) { ?>
                    <?php echo $model->itemInfo->getIcon() ?>
                <?php } ?>
            </div>
            <?php echo $form->textField($model, 'item_name', ['placeholder' => $model->getAttributeLabel('item_name'), 'class' => 'form-control js-item-name']) ?>
            <p class="help-block">Название пишется по Русски (База синхронизирована с РУофом</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'item_id', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'item_id', ['placeholder' => $model->getAttributeLabel('item_id'), 'class' => 'form-control js-item-id']) ?>
            <p class="help-block">57 - Адена, 4037 - Coin of luck</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'count', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'count', ['placeholder' => $model->getAttributeLabel('count'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'enchant', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'enchant', ['placeholder' => $model->getAttributeLabel('enchant'), 'class' => 'form-control']) ?>
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