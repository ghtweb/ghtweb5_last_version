<?php
/**
 * @var GamesServerShopCategoriesController $this
 * @var ShopCategoryForm $formModel
 * @var Gs $gs
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\ShopCategoryForm;

$title__ = 'Создание категории';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    'Сервера' => ['/backend/gameServers/index'],
    $gs->name . ' - Магазин' => ['index', 'gs_id' => $gs->id],
    ($formModel->isNewRecord ? 'Создание категории' : 'Редактирование категории'),
] ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]) ?>

    <?php echo $form->errorSummary($formModel) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'name', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'name', ['placeholder' => $formModel->getAttributeLabel('name'), 'class' => 'form-control']) ?>
            <p class="help-block">Как будет называться раздел в магазине. К примеру "Оружие"</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'sort', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'sort', ['placeholder' => $formModel->getAttributeLabel('sort'), 'class' => 'form-control']) ?>
            <p class="help-block">Порядок сортировки категорий в магазине</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'status', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($formModel, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo $formModel->isNewRecord ? 'Создать' : 'Сохранить' ?></button>
            <?php echo CHtml::link('назад', ['index', 'gs_id' => $gs->id], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php $this->endWidget() ?>

