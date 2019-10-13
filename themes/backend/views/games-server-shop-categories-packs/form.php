<?php
/**
 * @var ActiveForm $form
 * @var Gs $gs
 * @var ShopCategories $category
 * @var ShopItemsPackForm $formModel
 */

use app\modules\backend\models\forms\ShopItemsPackForm;

js(app()->getThemeManager()->getBaseUrl() . '/js/game-servers_shop-categories_packs_form.js', CClientScript::POS_END);

$title__ = 'Наборы';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    'Сервера' => ['/backend/gameServers/index'],
    $gs->name . ' - ' . 'Магазин' => ['/backend/gamesServerShopCategories/index', 'gs_id' => $gs->id],
    'Наборы для категории - ' . $category->name => ['index', 'gs_id' => $gs->id, 'category_id' => $category->id],
    ($formModel->isNewRecord ? 'Создание набора' : 'Редактирование набора'),
] ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data',
    ],
]) ?>

<?php echo $form->errorSummary($formModel) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'title', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'title', ['placeholder' => $formModel->getAttributeLabel('title'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'description', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($formModel, 'description', ['placeholder' => $formModel->getAttributeLabel('description'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'sort', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'sort', ['placeholder' => $formModel->getAttributeLabel('sort'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'status', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($formModel, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'img', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php if (!$formModel->isNewRecord && $formModel->imgIsExists('original')) { ?>
                <div class="img-wrap" style="margin-bottom: 20px">
                    <?php echo CHtml::image($formModel->getImgUrl('original')) ?><br>
                    <?php echo CHtml::link('Удалить картинку', ['delImg',
                        'gs_id' => $gs->getPrimaryKey(),
                        'category_id' => $category->getPrimaryKey(),
                        'pack_id' => $formModel->getPrimaryKey()
                    ], ['class' => 'js-del-image']) ?>
                </div>
            <?php } ?>
            <?php echo $form->fileField($formModel, 'img') ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo $formModel->isNewRecord ? 'Создать' : 'Сохранить' ?></button>
            <?php echo CHtml::link('назад', ['index', 'gs_id' => $gs->id, 'category_id' => $category->id], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php $this->endWidget() ?>

<?php tinymce([CHtml::activeId($formModel, 'description')]) ?>