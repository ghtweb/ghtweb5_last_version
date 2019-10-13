<?php
/**
 * @var CArrayDataProvider $dataProvider
 */

$this->pageTitle = 'Новости с форума';
?>

<?php if (!is_string($dataProvider)) { ?>

    <div class="entry">
        <div class="scroll-pane">
            <?php foreach ($dataProvider->getData() as $row) { ?>
                <div>
                    <header>
                        <h1><?php echo CHtml::link($row['title'], $row['link']) ?></h1>
                    </header>
                    <?php if (isset($row['description'])) { ?>
                        <div class="desc"><?php echo $row['description'] ?></div>
                    <?php } ?>
                    <footer>
                        <ul>
                            <li>Дата: <span class="label label-info"><?php echo $row['date'] ?></span></li>
                            <li><?php echo CHtml::link('Подробнее', $row['link']) ?></li>
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
        <?php echo $dataProvider ?>
    </div>
<?php } ?>