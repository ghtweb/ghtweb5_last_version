<table class="table">
    <tr class="divider">
        <td colspan="2">Разное</td>
    </tr>
    <tr>
        <td width="30%">В игре</td>
        <td width="70%"><?php echo $countOnline ?></td>
    </tr>
    <tr class="even">
        <td>Аккаунтов</td>
        <td><?php echo $countAccounts ?></td>
    </tr>
    <tr>
        <td>Персонажей</td>
        <td><?php echo $countCharacters ?></td>
    </tr>
    <tr class="even">
        <td>Кланов</td>
        <td><?php echo $countClans ?></td>
    </tr>
    <tr>
        <td>Мужчин</td>
        <td><?php $percent = ceil(($countMen / ($countFemale + $countMen)) * 100) ?>
            <div class="progress-bar">
                <span class="text"><?php echo $percent ?>% (<?php echo $countMen ?>)</span>
                <span class="line-bg" style="width: <?php echo $percent ?>%;"></span>
            </div>
        </td>
    </tr>
    <tr class="even">
        <td>Женщин</td>
        <td><?php $percent = ceil(($countFemale / ($countFemale + $countMen)) * 100) ?>
            <div class="progress-bar">
                <span class="text"><?php echo $percent ?>% (<?php echo $countFemale ?>)</span>
                <span class="line-bg" style="width: <?php echo $percent ?>%;"></span>
            </div>
        </td>
    </tr>
</table>
<table class="table stats-table">
    <tr class="divider">
        <td colspan="2">Расы</td>
    </tr>
    <tr>
        <td width="30%">Люди</td>
        <td width="70%">
            <div class="progress-bar">
                <span class="text"><?php echo $races_percentage['human'] ?>% (<?php echo $races['human'] ?>)</span>
                <span class="line-bg" style="width: <?php echo $races_percentage['human'] ?>%;"></span>
            </div>
        </td>
    </tr>
    <tr class="even">
        <td>Эльфы</td>
        <td>
            <div class="progress-bar">
                <span class="text"><?php echo $races_percentage['elf'] ?>% (<?php echo $races['elf'] ?>)</span>
                <span class="line-bg" style="width: <?php echo $races_percentage['elf'] ?>%;"></span>
            </div>
        </td>
    </tr>
    <tr>
        <td>Темные Эльфы</td>
        <td>
            <div class="progress-bar">
                <span class="text"><?php echo $races_percentage['dark_elf'] ?>% (<?php echo $races['dark_elf'] ?>
                    )</span>
                <span class="line-bg" style="width: <?php echo $races_percentage['dark_elf'] ?>%;"></span>
            </div>
        </td>
    </tr>
    <tr class="even">
        <td>Орки</td>
        <td>
            <div class="progress-bar">
                <span class="text"><?php echo $races_percentage['ork'] ?>% (<?php echo $races['ork'] ?>)</span>
                <span class="line-bg" style="width: <?php echo $races_percentage['ork'] ?>%;"></span>
            </div>
        </td>
    </tr>
    <tr>
        <td>Гномы</td>
        <td>
            <div class="progress-bar">
                <span class="text"><?php echo $races_percentage['dwarf'] ?>% (<?php echo $races['dwarf'] ?>)</span>
                <span class="line-bg" style="width: <?php echo $races_percentage['dwarf'] ?>%;"></span>
            </div>
        </td>
    </tr>
    <?php if (isset($races_percentage['kamael'])) { ?>
        <tr class="even">
            <td>Камаэли</td>
            <td>
                <div class="progress-bar">
                    <span class="text"><?php echo $races_percentage['kamael'] ?>% (<?php echo $races['kamael'] ?>
                        )</span>
                    <span class="line-bg" style="width: <?php echo $races_percentage['kamael'] ?>%;"></span>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>
<table class="table">
    <tr class="divider">
        <td colspan="2">Рейты</td>
    </tr>
    <tr>
        <td>Exp</td>
        <td><?php echo $this->_gs->exp ?></td>
    </tr>
    <tr class="even">
        <td>Sp</td>
        <td><?php echo $this->_gs->sp ?></td>
    </tr>
    <tr>
        <td>Adena</td>
        <td><?php echo $this->_gs->adena ?></td>
    </tr>
    <tr class="even">
        <td>Drop</td>
        <td><?php echo $this->_gs->drop ?></td>
    </tr>
    <tr>
        <td>Items</td>
        <td><?php echo $this->_gs->items ?></td>
    </tr>
    <tr class="even">
        <td>Spoil</td>
        <td><?php echo $this->_gs->spoil ?></td>
    </tr>
    <tr>
        <td>Quest drop</td>
        <td><?php echo $this->_gs->q_drop ?></td>
    </tr>
    <tr class="even">
        <td>Quest reward</td>
        <td><?php echo $this->_gs->q_reward ?></td>
    </tr>
    <tr>
        <td>Raid boss</td>
        <td><?php echo $this->_gs->rb ?></td>
    </tr>
    <tr class="even">
        <td>Epic Raid boss</td>
        <td><?php echo $this->_gs->erb ?></td>
    </tr>
</table>
