<?php
/**
 * @var string $charName
 * @var int $baseClass
 * @var int $level
 */
?>

<div class="character-short-info">
    <div class="character-short-info__char-name">
        <?php echo CHtml::encode($charName) ?>
    </div>
    <p class="help-block" style="font-size: 13px;">
        <span class="character-short-info__base-class"><?php echo Lineage::getClassName($baseClass) ?></span>
        [<span class="character-short-info__level"><?php echo $level ?></span>]
    </p>
</div>
