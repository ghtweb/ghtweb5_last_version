<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col style="width: 30%;">
        <col style="width: 10%;">
        <col style="width: 15%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 20%;">
    </colgroup>
    <thead>
    <tr>
        <th>Место</th>
        <th>Название</th>
        <th>Уровень</th>
        <th>Замок</th>
        <th>Игроков</th>
        <th>Репутация</th>
        <th>Альянс</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($content) { ?>
        <?php foreach ($content as $i => $row) { ?>
            <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                <td><?php echo ++$i ?></td>
                <td>
                    <?php echo clanAllyCrest('ally', $row['ally_id'], $this->_gs_id, $row['ally_crest']) .
                        clanAllyCrest('clan', $row['clan_id'], $this->_gs_id, $row['clan_crest']) ?>
                    <?php

                    if ($this->_gs->stats_clan_info) {
                        echo CHtml::link($row['clan_name'], ['/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $row['clan_id']]);
                    } else {
                        echo '<font color="#9D6A1E">' . e($row['clan_name']) . '</font>';
                    }
                    ?>
                    <p class="help-block">Лидер: <?php echo $row['char_name'] ?>
                        [<?php echo Lineage::getClassName($row['base_class']) ?>][<?php echo $row['level'] ?>]</p>
                </td>
                <td><?php echo $row['clan_level'] ?></td>
                <td><?php echo($row['hasCastle'] != 0 ? Lineage::getCastleName($row['hasCastle']) : '-') ?></td>
                <td><?php echo $row['ccount'] ?></td>
                <td><?php echo number_format($row['reputation_score'], 0, '', '.') ?></td>
                <td>
                    <?php echo($row['ally_name'] != '' ? e($row['ally_name']) : '-') ?>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="7">Данных нет</td>
        </tr>
    <?php } ?>
    </tbody>
</table>