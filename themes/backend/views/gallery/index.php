<?php
/**
 * @var CActiveDataProvider $dataProvider
 * @var Gallery[] $data
 */

$title_ = 'Галерея';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

<p><?php echo CHtml::link('Добавить картинку', ['form'], ['class' => 'btn btn-primary btn-block']) ?></p>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php if ($data = $dataProvider->getData()) { ?>

    <style>
        .gallery-list .options,
        .gallery-list {
            font-size: 0;
        }

        .gallery-list > li > a {
            margin-bottom: 7px;
        }

        .gallery-list .options > li,
        .gallery-list > li {
            margin: 0 10px 10px 0;
            display: inline-block;
            vertical-align: bottom;
            font-size: 16px;
        }
    </style>

    <ul class="list-unstyled gallery-list">
        <?php foreach ($data as $row) { ?>
            <li>
                <a href="<?php echo $row->getImgUrl('original') ?>" target="_blank" class="img-thumbnail">
                    <?php echo CHtml::image($row->getImgUrl('thumb')) ?>
                </a>
                <ul class="list-unstyled options">
                    <li><?php echo CHtml::link('', ['form', 'id' => $row->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                    <li><?php echo CHtml::link('', ['allow', 'id' => $row->id], ['class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                    <li><?php echo CHtml::link('', ['del', 'id' => $row->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                </ul>
            </li>
        <?php } ?>
    </ul>

    <?php $this->widget('CLinkPager', [
        'pages' => $dataProvider->getPagination(),
    ]) ?>

<?php } else { ?>
    <div class="alert alert-info">
        Нет данных
    </div>
<?php } ?>
