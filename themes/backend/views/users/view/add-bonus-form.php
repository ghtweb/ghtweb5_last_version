<?php
/**
 * @var UsersController $this
 * @var AddingBonusToUserForm $model
 * @var ActiveForm $form
 */

use app\modules\backend\models\forms\AddingBonusToUserForm;

?>

<?php if ($model->getBonusesList()) { ?>

    <?php $form = $this->beginWidget('ActiveForm', [
        'id' => 'add-bonus-form',
        'htmlOptions' => [
            'class' => 'form-horizontal js-add-bonus-form',
        ],
    ]) ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'bonus_id', ['class' => 'col-sm-3 control-label']) ?>
            <div class="col-sm-9">
                <?php echo $form->dropDownList($model, 'bonus_id', $model->getBonusesList(), ['class' => 'form-control']) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </div>

    <?php $this->endWidget() ?>
<?php } else { ?>

    <div class="alert alert-warning">
        Нет доступных бонусов
    </div>

    <?php echo CHtml::link('Перейти к созданию бонуса', ['/backend/bonuses/add'], ['class' => 'btn btn-success btn-block']) ?>

<?php } ?>

