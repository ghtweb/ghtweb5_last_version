<?php
/**
 * @var GamesServerShopCategoriesPacksItemsController $this
 * @var Gs $gs
 * @var ShopCategories $category
 * @var ShopItemsPacks $pack
 * @var ShopPackItemsForm $formModel
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\ShopPackItemsForm;

js(app()->getThemeManager()->getBaseUrl() . '/js/game-servers_shop-categories_packs_items_add.js', CClientScript::POS_END);

$title__ = 'Наборы';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    'Сервера' => ['/backend/gameServers/index'],
    $gs->name . ' - ' . 'Магазин' => ['/backend/gamesServerShopCategories/index', 'gs_id' => $gs->id],
    'Наборы для категории - ' . $category->name => ['/backend/gamesServerShopCategoriesPacks/index', 'gs_id' => $gs->id, 'category_id' => $category->id],
    'Предметы в наборе - ' . $pack->title => ['index', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id],
    ($this->getAction()->id == 'shopCategoryPackCreateItem' ? 'Добавление предмета' : 'Редактирование предмета'),
] ?>

    <div class="js-urlItemInfo hidden"><?php echo $this->createUrl('/backend/default/getItemInfo') ?></div>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]) ?>

<?php echo $form->errorSummary($formModel) ?>

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
    </p><br>

    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'item_name', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9 img-block">
            <div class="img js-item-icon">
                <?php if (!$formModel->isNewRecord) { ?>
                    <?php echo $formModel->itemInfo->getIcon() ?>
                <?php } ?>
            </div>
            <?php echo $form->textField($formModel, 'item_name', ['placeholder' => $formModel->getAttributeLabel('item_name'), 'class' => 'form-control js-item-name']) ?>
            <p class="help-block">Название пишется по английски (База синхронизирована с Euro клиентом)</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'item_id', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'item_id', ['placeholder' => $formModel->getAttributeLabel('item_id'), 'class' => 'form-control js-item-id']) ?>
            <p class="help-block">57 - Адена, 4037 - Coin of luck</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'description', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($formModel, 'description', ['placeholder' => $formModel->getAttributeLabel('description'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'cost', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'cost', ['placeholder' => $formModel->getAttributeLabel('cost'), 'class' => 'form-control']) ?>
            <p class="help-block">Сколько <b><?php echo $gs->currency_name ?></b> отдадут за все предметы.</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'discount', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'discount', ['placeholder' => $formModel->getAttributeLabel('discount'), 'class' => 'form-control']) ?>
            <p class="help-block">Вводите только число, без знака %.</p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'count', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'count', ['placeholder' => $formModel->getAttributeLabel('count'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'enchant', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($formModel, 'enchant', ['placeholder' => $formModel->getAttributeLabel('enchant'), 'class' => 'form-control']) ?>
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
            <?php echo $form->dropDownList($formModel, 'status', $formModel->getStatusList(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit"
                    class="btn btn-primary"><?php echo $this->getAction()->id == 'shopCategoryPackCreateItem' ? 'Создать' : 'Сохранить' ?></button>
            <?php echo CHtml::link('назад', ['index', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php $this->endWidget() ?>