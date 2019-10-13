<?php
/**
 * @var array $items
 * @var string $currency
 */

$totalSum = 0;
?>

    <font color="#ead255" face="Trebuchet MS"style="font-size: 24px;">Здравствуйте!</font>
    <br><br><br><br>
Вы только что совершили покупку в нашем магазине.<br><br>

<?php if ($items) { ?>
    <table style="color: #FFFFFF;" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Кол-во</th>
            <th>Заточка</th>
            <th>Скидк</th>
            <th>Итоговая цена</th>
        </tr>
        </thead>
        <?php $i = 1;
        foreach ($items as $item) { ?>
            <?php $totalSum += $item['total_sum'] ?>
            <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo e($item['name']) ?></td>
                <td><?php echo $item['count'] ?></td>
                <td><?php echo $item['enchant'] ?></td>
                <td><?php echo $item['discount'] ?>%</td>
                <td><?php echo $item['cost_per_one_discount'] * $item['count'] ?> <?php echo $currency ?></td>
            </tr>
        <?php } ?>
        <tfoot>
        <td colspan="6">Итого: <?php echo $totalSum ?> <?php echo $currency ?></td>
        </tfoot>
    </table>
<?php } ?>

    <br>
Спасибо за Вашу помощь в развитии проекта.