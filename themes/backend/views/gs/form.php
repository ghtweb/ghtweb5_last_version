<?php
/**
 * @var GameServersController $this
 * @var Gs $model
 * @var ActiveForm $form
 */

Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

$title__ = 'Игровые сервера';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    $title__ => ['index'],
    ($model->isNewRecord ? 'Добавление' : 'Редактирование'),
];
?>


<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]) ?>

<?php echo $form->errorSummary($model) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<legend>Разное</legend>

<div class="form-group">
    <?php echo $form->labelEx($model, 'name', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'name', ['placeholder' => $model->getAttributeLabel('name'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'ip', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'ip', ['placeholder' => $model->getAttributeLabel('ip'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'port', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'port', ['placeholder' => $model->getAttributeLabel('port'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'login_id', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'login_id', CHtml::listData(Ls::model()->findAll(), 'id', 'name'), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'version', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'version', serverVersionList(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'status', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'status', $model->getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'allow_teleport', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'allow_teleport', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'teleport_time', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'teleport_time', ['placeholder' => $model->getAttributeLabel('teleport_time'), 'class' => 'form-control']) ?>
        <p class="help-block">Время в минутах через сколько игрок сможет повторно телепортироваться.</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'fake_online', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'fake_online', ['placeholder' => $model->getAttributeLabel('fake_online'), 'class' => 'form-control']) ?>
        <p class="help-block">Указывайте в процентах, к примеру игроков в игре 100 если вписать 10 то выведет на сайте
            110. 0 - отключит накрутку</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'online_txt_allow', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'online_txt_allow', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<legend>Подключение к базе данных</legend>

<div class="form-group">
    <?php echo $form->labelEx($model, 'db_host', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'db_host', ['placeholder' => $model->getAttributeLabel('db_host'), 'class' => 'form-control']) ?>
        <p class="help-block">IP адрес БД сервера.</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'db_port', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'db_port', ['placeholder' => $model->getAttributeLabel('db_port'), 'class' => 'form-control']) ?>
        <p class="help-block">Порт от БД сервера.</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'db_user', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'db_user', ['placeholder' => $model->getAttributeLabel('db_user'), 'class' => 'form-control']) ?>
        <p class="help-block">Пользователь от БД сервера.</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'db_pass', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->passwordField($model, 'db_pass', ['placeholder' => $model->getAttributeLabel('db_pass'), 'class' => 'form-control']) ?>
        <p class="help-block">Пароль от БД сервера.</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'db_name', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'db_name', ['placeholder' => $model->getAttributeLabel('db_name'), 'class' => 'form-control']) ?>
        <p class="help-block">Название БД сервера.</p>
    </div>
</div>

<legend>Статистика</legend>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_allow', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_allow', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_total', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_total', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_pvp', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_pvp', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_pk', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_pk', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_clans', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_clans', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_castles', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_castles', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_online', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_online', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_clan_info', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_clan_info', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_top', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_top', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_rich', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_rich', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_items', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'stats_items', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_items_list', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'stats_items_list', ['placeholder' => $model->getAttributeLabel('stats_items_list'), 'class' => 'form-control']) ?>
        <p class="help-block">Вводить ID предметов через запятую, к примеру: 57, 4037, 567</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_cache_time', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'stats_cache_time', ['placeholder' => $model->getAttributeLabel('stats_cache_time'), 'class' => 'form-control']) ?>
        <p class="help-block">В минутах.</p>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'stats_count_results', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'stats_count_results', ['placeholder' => $model->getAttributeLabel('stats_count_results'), 'class' => 'form-control']) ?>
        <p class="help-block">Сколько строк вывести на странице.</p>
    </div>
</div>

<legend>Рейты</legend>

<div class="form-group">
    <?php echo $form->labelEx($model, 'spoil', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'spoil', ['placeholder' => $model->getAttributeLabel('spoil'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'q_drop', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'q_drop', ['placeholder' => $model->getAttributeLabel('q_drop'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'q_reward', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'q_reward', ['placeholder' => $model->getAttributeLabel('q_reward'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'rb', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'rb', ['placeholder' => $model->getAttributeLabel('rb'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'erb', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'erb', ['placeholder' => $model->getAttributeLabel('erb'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'exp', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'exp', ['placeholder' => $model->getAttributeLabel('exp'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'sp', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'sp', ['placeholder' => $model->getAttributeLabel('sp'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'adena', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'adena', ['placeholder' => $model->getAttributeLabel('adena'), 'class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'drop', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'drop', ['placeholder' => $model->getAttributeLabel('drop'), 'class' => 'form-control']) ?>
    </div>
</div>

<legend>Разное</legend>

<div class="form-group">
    <?php echo $form->labelEx($model, 'currency_name', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'currency_name', ['class' => 'form-control']) ?>
        <p class="help-block">Название внутриигровой валюты на сервере. К примеру - COL</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'deposit_allow', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'deposit_allow', ActiveRecord::getStatusListWithoutDelete(), ['class' => 'form-control']) ?>
        <p class="help-block">Вкл/Выкл возможность пополнить баланс своего аккаунта.</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'deposit_payment_system', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->dropDownList($model, 'deposit_payment_system', $model->getAggregatorsList(), ['class' => 'form-control']) ?>
        <p class="help-block">Платёжная система через которую игрок сможет пополнять свой баланс.</p>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'deposit_desc', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'deposit_desc', ['class' => 'form-control']) ?>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model, 'deposit_course_payments', ['class' => 'col-lg-3 control-label']) ?>
    <div class="col-lg-9">
        <?php echo $form->textField($model, 'deposit_course_payments', ['class' => 'form-control']) ?>
        <p class="help-block">Сколько реальных денег юзер будет отдавать за одну игровую валюту.</p>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        <button type="submit"
                class="btn btn-primary"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить') ?></button>
    </div>
</div>

<?php $this->endWidget() ?>
