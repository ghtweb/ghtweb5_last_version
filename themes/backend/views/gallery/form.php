<?php
/**
 * @var GalleryForm $model
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\GalleryForm;

$title__ = 'Галерея';
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
            <?php echo $form->textField($model, 'title', ['placeholder' => $model->getAttributeLabel('title'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'img', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php if ($model->scenario == 'update' && $model->imgIsExists('thumb')) { ?>
                <a href="<?php echo $model->getImgUrl('original') ?>" target="_blank">
                    <img src="<?php echo $model->getImgUrl('thumb') ?>" alt="" style="max-width: 150px">
                </a>
                <br><br>
            <?php } ?>
            <?php echo $form->fileField($model, 'img') ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'sort', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'sort', ['placeholder' => $model->getAttributeLabel('sort'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit"
                    class="btn btn-primary"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить') ?></button>
            <?php echo CHtml::link('назад', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php $this->endWidget() ?>