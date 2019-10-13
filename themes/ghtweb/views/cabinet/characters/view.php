<?php

$title_ = 'Персонажи';
$this->pageTitle = $title_;

$this->breadcrumbs = [
    $title_ => ['/cabinet/characters/index'],
    'Персонаж' . ' - ' . $character['char_name']
];
?>

<h4>Информация</h4>

<table class="table">
    <tbody>
    <tr>
        <td><b>Имя</b></td>
        <td><?php echo $character['char_name'] ?></td>
        <td><b>Пол</b></td>
        <td><?php echo Lineage::getGender($character['sex']) ?></td>
        <td><b>Уровень</b></td>
        <td><?php echo $character['level'] ?></td>
    </tr>
    <tr class="even">
        <td><b>Клан</b></td>
        <td><?php echo $character['clan_name'] ? e($character['clan_name']) : 'нет' ?></td>
        <td><b>Тюрьма</b></td>
        <td><?php echo !empty($character['jail']) ? 'в тюрьме' : 'не в тюрьме' ?></td>
        <td><b>Основной класс</b></td>
        <td><?php echo Lineage::getClassName($character['base_class']) ?></td>
    </tr>
    <tr>
        <td><b>Карма</b></td>
        <td><?php echo $character['karma'] ?></td>
        <td><b>ПВП</b></td>
        <td><?php echo $character['pvpkills'] ?></td>
        <td><b>ПК</b></td>
        <td><?php echo $character['pkkills'] ?></td>
    </tr>
    <tr class="even">
        <td><b>Титул</b></td>
        <td><?php echo($character['title'] ? e($character['title']) : 'нет') ?></td>
        <td><b>Статус</b></td>
        <td><?php echo($character['online'] ? 'в игре' : 'не в игре') ?></td>
        <td><b>Время в игре</b></td>
        <td><?php echo Lineage::getOnlineTime($character['onlinetime']) ?></td>
    </tr>
    <tr>
        <td><b>Exp</b></td>
        <td><?php echo number_format($character['exp'], 0, '', '.') ?></td>
        <td><b>Sp</b></td>
        <td><?php echo number_format($character['sp'], 0, '', '.') ?></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>

<h4>Предметы</h4>

<table class="table">
    <thead>
    <tr>
        <th></th>
        <th>Название</th>
        <th>Кол-во</th>
        <th>Заточка</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($items) { ?>
        <?php $i = 0;
        foreach ($items as $item) { ?>
            <?php

            // Если по какой-то причине в инвентаре находится предмет которого нет в базе предметов CMS, то не показываю его
            if (!isset($item['icon'])) {
                continue;
            }
            ?>
            <tr class="<?php echo $i++ % 2 == 0 ? 'odd' : 'even' ?>">
                <td><?php echo Lineage::getItemIcon($item['icon'], $item['description']) ?></td>
                <td><?php echo e($item['name']) ?><?php echo Lineage::getItemGrade($item['crystal_type']) ?></td>
                <td><?php echo number_format($item['count'], 0, '', '.') ?></td>
                <td><?php echo $item['enchant_level'] ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="4">Нет данных.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>