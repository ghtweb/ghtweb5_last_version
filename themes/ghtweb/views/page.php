<?php
/**
 * @var Controller $this
 * @var Pages $model
 */

$this->pageTitle = !empty($model->seo_title) ? $model->seo_title : $model->title;
?>

<h2 class="title"><?php echo e($model->title) ?></h2>
<div class="entry">
    <div class="scroll-pane">
        <?php echo $model->text ?>
    </div>
</div>