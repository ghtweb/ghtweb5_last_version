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

    <h2><?php echo e($this->pageHeader) ?></h2>
<?php prt(app()->errorHandler->error) ?>