<?php

$this->pageTitle = 'Настройки';
$this->breadcrumbs = ['Настройки'];

js(app()->getThemeManager()->getBaseUrl() . '/js/config_index.js', CClientScript::POS_END);
?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
]) ?>

<ul class="nav nav-tabs">
    <?php foreach ($model as $i => $group) { ?>
        <li<?php echo($i == 0 ? ' class="active"' : '') ?>>
            <a href="#config-<?php echo $i ?>" data-toggle="tab"><?php echo e($group->name) ?></a>
        </li>
    <?php } ?>
</ul>

<div class="tab-content">
    <?php foreach ($model as $i => $group) { ?>
        <div class="tab-pane fade<?php echo($i == 0 ? ' in active' : '') ?>" id="config-<?php echo $i ?>">
            <?php foreach ($group->config as $config) { ?>
                <div class="form-group" data-group="<?php echo $group->getPrimaryKey() ?>"
                     data-id="<?php echo $config->getPrimaryKey() ?>">
                    <label class="control-label">
                        <span class="glyphicon glyphicon-align-justify" rel="tooltip" title="Сортировка (зажмите кнопку и тащите настройку вверх или вниз)"></span>
                        <?php echo e($config->label) ?>
                        <a class="glyphicon glyphicon-retweet" title="Отменить" rel="tooltip"
                           data-fieldname="<?php echo $config->param ?>"></a>
                    </label>
                    <?php echo $config->getField() ?>
                </div>
            <?php } ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    <?php } ?>
</div>

<?php $this->endWidget() ?>
