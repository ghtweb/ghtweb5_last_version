<?php
/**
 * @var News $model
 */

$this->pageTitle = !empty($model->seo_title) ? $model->seo_title : $model->title;
?>

<h2 class="title"><?php echo e($model->title) ?></h2>

<div class="entry news">
    <div class="scroll-pane">
        <div class="desc">
            <?php echo $model->text ?>
        </div>
        <div class="clearfix"></div>
        <?php if (config('news.detail.socials') == 1) { ?>
            <?php $this->widget('app.widgets.NewsSocials.NewsSocials', [
                'params' => [
                    'data-url' => $this->createAbsoluteUrl('/news/default/detail', ['news_id' => $model->id]),
                    'data-title' => e($model->title),
                    'data-image' => $model->imgIsExists('original') ? $model->getImgUrl('original') : '',
                    'data-description' => $model->description,
                ]
            ]) ?>
        <?php } ?>

        <?php echo CHtml::link('Назад', ['/news/default/index']) ?>
    </div>
</div>