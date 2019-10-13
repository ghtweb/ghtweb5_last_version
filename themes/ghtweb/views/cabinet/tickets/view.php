<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $answersDataProvider
 * @var TicketsAnswers[] $answers
 * @var TicketsAnswers $model
 * @var Tickets $ticket
 * @var ActiveForm $form
 */

$assetsUrl = app()->getThemeManager()->getBaseUrl();

$title_ = 'Поддержка - просмотр тикета';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    'Поддержка' => ['/cabinet/tickets/index'],
    'Тикет - ' . CHtml::encode($ticket->title)
];
?>

<?php if ($ticket->isStatusOff()) { ?>
    <div class="alert alert-info">
        Тикет закрыт
    </div>
<?php } ?>

    <div class="entry">
        <div class="scroll-pane">
            <?php if ($answers = $answersDataProvider->getData()) { ?>
                <ul class="list-unstyled answers clearfix">
                    <?php foreach ($answers as $answer) { ?>
                        <li class="clearfix">
                            <figure>
                                <?php if (user()->getId() == $answer->user_id) { ?>
                                    <?php echo CHtml::image($assetsUrl . '/images/tiket_03.png'); ?>
                                <?php } else { ?>
                                    <?php echo CHtml::image($assetsUrl . '/images/tiket_24.png'); ?>
                                <?php } ?>
                            </figure>
                            <div class="info">
                                <span class="author"><?php echo(user()->getId() == $answer->user_id ? 'Вы' : 'Админ') ?></span>
                                <p class="date"><?php echo $answer->getDate() ?></p>
                                <p class="text"><?php echo nl2br(e($answer->text)) ?></p>
                            </div>
                        </li>
                    <?php } ?>
                </ul>

                <?php $this->widget('CLinkPager', [
                    'pages' => $answersDataProvider->getPagination(),
                ]) ?>

            <?php } else { ?>
                <div class="alert alert-warning">
                    Нет данных.
                </div>
            <?php } ?>
        </div>
    </div>

<?php if ($ticket->status == ActiveRecord::STATUS_ON) { ?>

    <h2>Добавить ответ</h2>
    <?php $form = $this->beginWidget('ActiveForm', [
        'id' => $this->getId() . '-form',
        'htmlOptions' => [
            'class' => 'form-horizontal',
        ]
    ]) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'text', ['class' => 'col-lg-3 control-label']) ?>
        <div class="field">
            <?php echo $form->textArea($model, 'text', ['placeholder' => $model->getAttributeLabel('text'), 'class' => 'form-control']) ?>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span>Ответить</span>
        </button>
    </div>

    <?php $this->endWidget() ?>

<?php } ?>