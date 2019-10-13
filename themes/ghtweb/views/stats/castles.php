<table class="table">
    <tbody>
    <?php $i = 0;
    foreach ($content as $row) { ?>
        <tr>
            <th colspan="2"><?php echo Lineage::getCastleName($row['castle_id']) ?></th>
        </tr>
        <tr class="<?php echo ++$i % 2 == 0 ? 'odd' : 'even' ?>">
            <td width="150"><?php echo Lineage::getCastleIcon($row['castle_id']) ?></td>
            <td>
                Налог: <i><?php echo $row['tax_percent'] ?>%</i><br/>
                Дата осады:
                <i><?php echo app()->getDateFormatter()->formatDateTime(substr($row['sieg_date'], 0, 10)) ?></i><br/>
                Владелец
                : <?php echo($row['owner'] ? ($this->_gs->stats_clan_info ? CHtml::link($row['owner'], ['/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $row['owner_id']]) : $row['owner']) : '<i>NPC</i>') ?>
                <br/>
                Нападающие:
                <?php

                $f = [];
                if ($row['forwards'] && is_array($row['forwards'])) {
                    foreach ($row['forwards'] as $fd) {
                        if ($this->_gs->stats_clan_info) {
                            $f[] = CHtml::link($fd['clan_name'], ['/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $fd['clan_id']]);
                        } else {
                            $f[] = $fd['clan_name'];
                        }
                    }
                } else {
                    $f[] = 'Нет';
                }
                echo implode(', ', $f);
                ?> <br/>
                'Защитники:
                <?php

                $f = [];
                if ($row['defenders'] && is_array($row['defenders'])) {
                    foreach ($row['defenders'] as $fd) {
                        if ($this->_gs->stats_clan_info) {
                            $f[] = CHtml::link($fd['clan_name'], ['/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $fd['clan_id']]);
                        } else {
                            $f[] = $fd['clan_name'];
                        }
                    }
                } else {
                    $f[] = 'Нет';
                }
                echo implode(', ', $f);
                ?> <br/>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>