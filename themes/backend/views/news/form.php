<?php
/**
 * @var NewsController $this
 * @var News $model
 * @var ActiveForm $form
 */

$title__ = 'Новости';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['index'],
    ($model->isNewRecord ? 'Добавление' : 'Редактирование'),
]
?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data',
    ],
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'title', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'title', [
                'placeholder' => $model->getAttributeLabel('title'),
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'description', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($model, 'description', [
                'placeholder' => $model->getAttributeLabel('description'),
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'text', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($model, 'text', [
                'placeholder' => $model->getAttributeLabel('text'),
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'img', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->fileField($model, 'img') ?>
            <?php if ($model->scenario == 'update' && $model->imgIsExists('original')) { ?>
                <a href="<?php echo $model->getImgUrl('original') ?>" target="_blank" style="text-decoration: none; display: inline-block; margin-top: 20px">
                    <img src="<?php echo $model->getImgUrl('original') ?>" alt="" style="max-width: 150px">
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'seo_title', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'seo_title', [
                'placeholder' => $model->getAttributeLabel('seo_title'),
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'seo_description', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'seo_description', [
                'placeholder' => $model->getAttributeLabel('seo_description'),
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'seo_keywords', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'seo_keywords', [
                'placeholder' => $model->getAttributeLabel('seo_keywords'),
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', ActiveRecord::getStatusListWithoutDelete(), [
                'class' => 'form-control',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit"
                    class="btn btn-primary"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>

<?php tinymce([CHtml::activeId($model, 'text'), CHtml::activeId($model, 'description')]) ?>