<?php
/**
 * @var BonusesController $this
 * @var CActiveDataProvider $dataProvider
 * @var UserBonuses[] $bonuses
 * @var \app\modules\cabinet\models\forms\ActivationUserBonusCodeForm $formModel
 * @var ActiveForm $form
 */

use app\helpers\Html;

$title_ = 'Мои бонусы';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<?php if ($bonuses = $dataProvider->getData()) { ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <?php echo Html::errorSummary($formModel) ?>

    <ul class="list-unstyled bonus-items">
        <?php foreach ($bonuses as $i => $bonus) { ?>
            <li>
                <h2><?php echo ++$i ?>) <?php echo CHtml::encode($bonus->bonusInfo->title) ?></h2>
                <table class="table">
                    <colgroup>
                        <col style="width: 5%;">
                        <col style="width: 40px;">
                        <col>
                        <col style="width: 15%;">
                        <col style="width: 15%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>Предмет</th>
                        <th>Кол-во</th>
                        <th>Заточка</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bonus->bonusInfo->items as $i2 => $item) { ?>
                        <tr>
                            <td><?php echo ++$i2 ?></td>
                            <td><?php echo Lineage::getItemIcon($item->itemInfo->icon, $item->itemInfo->name) ?></td>
                            <td><?php echo CHtml::encode($item->itemInfo->name) ?></td>
                            <td><?php echo number_format($item->count, 0, '', '.') ?></td>
                            <td><?php echo $item->enchant ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <p class="calendar">
                    <?php if ($bonus->isActivated()) { ?>
                        Бонус активирован (дата активации: <?php echo $bonus->getUpdatedAt('Y-m-d H:i') ?>, на персонажа: <?php echo CHtml::encode($bonus->char_name) ?>).
                    <?php } else { ?>
                        <?php if ($characters = $formModel->getCharacters()) { ?>

                            <?php $form = $this->beginWidget('ActiveForm', [
                                'method' => 'post',
                                'htmlOptions' => [
                                    'class' => 'form-inline',
                                ]
                            ]) ?>

                            <button type="submit" class="button">
                                <span>Активировать на персонажа</span>
                            </button>

                            <?php echo $form->hiddenField($formModel, 'bonus_id', ['value' => $bonus->bonus_id]) ?>
                            <?php echo $form->dropDownList($formModel, 'char_id', $characters, ['class' => 'form-control']) ?>

                            <?php $this->endWidget('ActiveForm') ?>

                        <?php } else { ?>
                            У Вас нет созданных персонажей, активация бонуса невозможна.
                        <?php } ?>
                    <?php } ?>
                </p>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <div class="alert alert-info">
        У Вас нет бонусов.
    </div>
<?php } ?>