<?php
/**
 * @var int $clanId
 * @var Gs $gs
 * @var string $clanCrest
 * @var string $clanName
 * @var bool $showClanLink
 */
?>
<div class="character-clan-info">
    <span class="character-clan-info__icon">
        <?php echo clanAllyCrest('clan', $clanId, $gs->id, $clanCrest) ?>
    </span>
    <?php if ($gs->stats_clan_info) {
        $clan_link = CHtml::link($clanName, ['/stats/default/index', 'gs_id' => $gs->id, 'type' => 'clan-info', 'clan_id' => $clanId], ['class' => 'character-clan-info__link']);
    } else {
        $clan_link = CHtml::encode($clanName);
    } ?>
    <span class="character-clan-info__clan-name">
        <?php echo empty($clanName) ? '-' : $clan_link ?>
    </span>
</div>
