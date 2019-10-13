<?php
/**
 * @var TicketsController $this
 * @var ActiveForm $form
 * @var TicketsCategories $model
 */

$title__ = 'Тикеты - категории';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['/backend/tickets/categories'],
    ($model->isNewRecord ? 'Добавление' : 'Редактирование'),
]
?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => 'tickets-category-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
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
        <?php echo $form->labelEx($model, 'sort', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'sort', ['placeholder' => $model->getAttributeLabel('sort'), 'class' => 'form-control']) ?>
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