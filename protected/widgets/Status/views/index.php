<?php
/**
 * @var int $status
 * @var string $statusText
 */
?>

<span class="label label-<?php echo $status == ActiveRecord::STATUS_ON ? 'success' : 'default' ?>">
    <?php echo $statusText ?>
</span>
