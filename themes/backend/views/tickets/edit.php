<?php
/**
 * @var TicketsController $this
 * @var Tickets $ticket
 * @var TicketsAnswers $model
 * @var CActiveDataProvider $answersDataProvider
 * @var TicketsAnswers[] $answers
 * @var TicketsAnswers $answer
 * @var ActiveForm $form
 */

$title__ = 'Тикеты';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['index'],
    $ticket->title . ' - ' . 'Просмотр',
] ?>

<h3>Информация о тикете</h3>
<table class="table">
    <tbody>
    <tr>
        <td width="30%">Автор</td>
        <td width="70%"><?php echo CHtml::link($ticket->user->login,
                ['/backend/users/view', 'user_id' => $ticket->user->user_id],
                ['title' => 'Перейти к просмотру пользователя', 'target' => '_blank', 'rel' => 'tooltip']) ?>
        </td>
    </tr>
    <tr>
        <td>Категория</td>
        <td><?php echo CHtml::encode($ticket->category->title) ?></td>
    </tr>
    <tr>
        <td>Приоритет</td>
        <td><?php echo $ticket->getPriority() ?></td>
    </tr>
    <tr>
        <td>Дата инцидента</td>
        <td><?php echo CHtml::encode($ticket->date_incident) ?></td>
    </tr>
    <tr>
        <td>Имя персонажа</td>
        <td><?php echo CHtml::encode($ticket->char_name) ?></td>
    </tr>
    <tr>
        <td>Дата создания тикета</td>
        <td><?php echo $ticket->getCreatedAt('Y:m:d H:i') ?></td>
    </tr>
    <tr>
        <td>Дата последнего ответа</td>
        <td><?php echo $ticket->getUpdatedAt('Y:m:d H:i') ?></td>
    </tr>
    </tbody>
</table>

<h3>Ответы</h3>
<hr>

<?php if ($answers = $answersDataProvider->getData()) { ?>

    <?php foreach ($answers as $answer) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Дата: <?php echo $answer->getCreatedAt('Y:m:d H:i') ?>
                От: <?php
                if (isset($answer->userInfo->login)) {
                    echo CHtml::link($answer->userInfo->login,
                        ['/backend/users/view', 'user_id' => $ticket->user->user_id],
                        ['title' => 'Перейти к просмотру пользователя', 'target' => '_blank', 'rel' => 'tooltip']);
                } else {
                    echo 'Админ';
                }
                ?>
            </div>
            <div class="panel-body">
                <?php echo nl2br(CHtml::encode($answer->text)) ?>
            </div>
        </div>
    <?php } ?>

    <?php $this->widget('CLinkPager', [
        'pages' => $answersDataProvider->getPagination(),
    ]) ?>

<?php } else { ?>
    <div class="alert alert-info">
        Нет данных.
    </div>
<?php } ?>

<h3>Добавить ответ</h3>

<?php if ($ticket->status == Tickets::STATUS_ON) { ?>

    <?php $form = $this->beginWidget('ActiveForm', [
        'id' => $this->getId() . '-form',
        'htmlOptions' => [
            'class' => 'form-horizontal',
        ],
    ]) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'text', ['class' => 'col-lg-3 control-label']) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($model, 'text', ['placeholder' => $model->getAttributeLabel('text'), 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">Добавить ответ</button>
        </div>
    </div>

    <?php $this->endWidget() ?>
<?php } else { ?>
    <div class="alert alert-warning">
        Нельзя добавить ответ в закрытый тикет.
    </div>
<?php } ?>

