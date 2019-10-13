<?php
/**
 * @var int $online
 */
?>

<?php if ($online == 1) { ?>
    <span class="status-online" title="В игре"></span>
<?php } elseif ($online == 0) { ?>
    <span class="status-offline" title="Не в игре"></span>
<?php } else { ?>
    <span class="status-unknown" title="n/a">n/a</span>
<?php } ?>
