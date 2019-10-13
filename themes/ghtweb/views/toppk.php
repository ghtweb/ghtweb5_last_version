<?php if (config('top.pk.allow')) { ?>
    <?php if ($content) { ?>
        <ul class="list-unstyled">
            <?php foreach ($content as $gsId => $row) { ?>
                <li>
                    <?php if (isset($row['error'])) { ?>
                        <?php echo $row['error'] ?>
                    <?php } else { ?>
                        <?php echo e($row['char_name']) ?> (<?php echo $row['level'] ?>) <span
                                class="label label-info pull-right"><?php echo e($row['pkkills']) ?></span>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        Нет данных.
    <?php } ?>
<?php } else { ?>
    Модуль отключен.
<?php } ?>