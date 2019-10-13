<?php
/**
 * @var CActiveDataProvider $dataProvider
 * @var News[] $data
 */

$this->pageTitle = 'Новости';
?>

<?php if ($data = $dataProvider->getData()) { ?>

    <div class="entry news">
        <div class="scroll-pane">
            <?php foreach ($data as $row) { ?>
                <div style="margin-bottom: 30px;">
                    <header>
                        <h1><?php echo CHtml::link($row->title, ['/news/default/detail', 'slug' => $row->slug]) ?></h1>
                    </header>
                    <div class="desc">
                        <?php if ($row->imgIsExists('original')) { ?>
                            <img src="<?php echo $row->getImgUrl('original') ?>" alt="" style="margin: 0 20px 20px 0; float: left;"/>
                        <?php } ?>
                        <?php echo $row->description ?>
                    </div>
                    <div class="clearfix"></div>
                    <footer>
                        <ul>
                            <li>Дата: <span class="label label-info"><?php echo $row->getDate() ?></span></li>
                        </ul>
                    </footer>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="news">
        <?php $this->widget('CLinkPager', [
            'pages' => $dataProvider->getPagination(),
        ]) ?>
    </div>
<?php } else { ?>
    <div class="alert alert-info">
        Нет данных.
    </div>
<?php } ?>