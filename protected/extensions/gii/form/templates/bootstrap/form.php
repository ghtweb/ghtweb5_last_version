<?php echo "<?php \$form=\$this->beginWidget('ActiveForm', array(
    'id' => '".$this->class2id($this->modelClass)."-form',
    'enableAjaxValidation' => FALSE,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
)) ?>\n"; ?>

    <?php echo "<?php echo \$form->errorSummary(\$model) ?>\n"; ?>

    <?php echo "<?php \$this->widget('app.widgets.FlashMessages.FlashMessages') ?>\n"; ?>

<?php foreach($this->getModelAttributes() as $attribute): ?>
    <div class="form-group">
        <?php echo "<?php echo \$form->labelEx(\$model, '$attribute', array('class' => 'col-lg-3 control-label')) ?>\n"; ?>
        <div class="col-lg-9">
            <?php echo "<?php echo \$form->textField(\$model, '$attribute', array('placeholder' => \$model->getAttributeLabel('$attribute'), 'class' => 'form-control')) ?>\n"; ?>
        </div>
    </div>

<?php endforeach; ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo "<?php echo Yii::t('backend', 'Создать') ?>" ?></button>
        </div>
    </div>

<?php echo "<?php \$this->endWidget() ?>\n"; ?>