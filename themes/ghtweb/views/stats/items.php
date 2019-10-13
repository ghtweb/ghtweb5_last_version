<?php
/**
 * @var Controller $this
 * @var array $content
 */

?>

<?php foreach ($content as $itemId => $row) { ?>
    <table class="table">
        <thead>
        <tr>
            <th colspan="7"><?php echo CHtml::encode($row['itemInfo']->name) ?> (общее
                кол-во: <?php echo number_format($row['maxTotalItems'], 0, '', '.') ?>, кол-во
                персонажей: <?php echo count($row['characters']) ?>)
            </th>
        </tr>
        </thead>
        <tbody>
        <?php if ($row['characters']) { ?>
            <tr>
                <td width="5%">#</td>
                <td>Персонаж</td>
                <td width="15%">PvP/PK</td>
                <td width="20%">Клан</td>
                <td width="15%">Время в игре</td>
                <td width="10%">Статус</td>
                <td width="15%">Кол-во</td>
            </tr>
            <?php foreach ($row['characters'] as $i => $character) { ?>
                <tr<?php echo($i % 2 == 0 ? ' class="even"' : '') ?>>
                    <td><?php echo ++$i ?></td>
                    <td><?php $this->widget('app.widgets.CharacterShortInfo.CharacterShortInfo', [
                            'charName' => $character['char_name'],
                            'baseClass' => $character['base_class'],
                            'level' => $character['level'],
                        ]) ?></td>
                    <td><?php echo $character['pvpkills'] ?>/<?php echo $character['pkkills'] ?></td>
                    <td><?php $this->widget('app.widgets.CharacterClanInfo.CharacterClanInfo', [
                            'clanId' => $character['clan_id'],
                            'gs' => $this->_gs,
                            'clanCrest' => $character['clan_crest'],
                            'clanName' => $character['clan_name'],
                            'showClanLink' => $this->_gs->stats_clan_info == 1,
                        ]) ?></td>
                    <td><?php echo Lineage::getOnlineTime($character['onlinetime']) ?></td>
                    <td><?php $this->widget('app.widgets.CharacterOnlineStatus.CharacterOnlineStatus', ['online' => $character['online']]) ?></td>
                    <td><?php echo number_format($character['maxCountItems'], 0, '', '.') ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7">Владельцев нет</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
