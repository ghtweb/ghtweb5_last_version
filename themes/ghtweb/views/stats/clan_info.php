<p>
    Состав клана: <?php echo $clan_info['clan_name'] ?><br>
    Алли
    : <?php echo($clan_info['ally_name'] ? e($clan_info['ally_name']) : 'Нет') ?><br>
    Замок
    : <?php echo($clan_info['hasCastle'] ? Lineage::getCastleName($clan_info['hasCastle']) : 'Нет') ?>
    <br>
    Лидер: <?php echo e($clan_info['char_name']) ?>
    (<?php echo Lineage::getClassName($clan_info['base_class']) ?> <?php echo $clan_info['level'] ?>)
</p>

<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col>
        <col style="width: 14%;">
        <col style="width: 21%;">
        <col style="width: 13%;">
    </colgroup>
    <thead>
    <tr>
        <th>Место</th>
        <th>Персонаж</th>
        <th>PvP/PK</th>
        <th>Время в игре</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($clanCharacters) { ?>
        <?php foreach ($clanCharacters as $i => $row) { ?>
            <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                <td><?php echo ++$i ?></td>
                <td><?php $this->widget('app.widgets.CharacterShortInfo.CharacterShortInfo', [
                        'charName' => $row['char_name'],
                        'baseClass' => $row['base_class'],
                        'level' => $row['level'],
                    ]) ?></td>
                <td><?php echo $row['pvpkills'] ?>/<?php echo $row['pkkills'] ?></td>
                <td><?php echo Lineage::getOnlineTime($row['onlinetime']) ?></td>
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