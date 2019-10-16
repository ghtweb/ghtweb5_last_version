<?php
/**
 * @var int $code
 * @var string $message
 * @var string $type
 * @var string $file
 * @var int $line
 */

$this->pageTitle = 'Ошика ' . $code;
?>
<h2><?php echo e($this->pageTitle) ?></h2>
<?php if (YII_DEBUG) { ?>
    <?php prt(app()->errorHandler->error) ?>
<?php } else { ?>
    <div class="alert alert-danger">
        <?php echo $message ?>
    </div>
<?php } ?>
