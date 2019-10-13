<?php
/**
 * @var array $content
 */
?>
<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col>
        <col style="width: 6%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>Место</th>
        <th>Персонаж</th>
        <th>PvP</th>
        <th>Клан</th>
        <th>Время в игре</th>
        <th>Adena</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($content) { ?>
        <?php foreach ($content as $i => $row) { ?>
            <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                <td><?php echo ++$i ?></td>
                <td><?php $this->widget('app.widgets.CharacterShortInfo.CharacterShortInfo', [
                        'charName' => $row['char_name'],
                        'baseClass' => $row['base_class'],
                        'level' => $row['level'],
                    ]) ?></td>
                <td><?php echo $row['pvpkills'] ?></td>
                <td><?php $this->widget('app.widgets.CharacterClanInfo.CharacterClanInfo', [
                        'clanId' => $row['clan_id'],
                        'gs' => $this->_gs,
                        'clanCrest' => $row['clan_crest'],
                        'clanName' => $row['clan_name'],
                        'showClanLink' => $this->_gs->stats_clan_info == 1,
                    ]) ?></td>
                <td><?php echo Lineage::getOnlineTime($row['onlinetime']) ?></td>
                <td><?php echo number_format($row['adena_count'], 0, '', '.') ?></td>
                <td><?php $this->widget('app.widgets.CharacterOnlineStatus.CharacterOnlineStatus', ['online' => $row['online']]) ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6">Данных нет</td>
        </tr>
    <?php } ?>
    </tbody>
</table>