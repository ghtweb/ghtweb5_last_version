<?php
/**
 * @var array $content
 * @var int $totalOnline
 */
?>

<?php if (config('server_status.allow')) { ?>
    <?php if ($content) { ?>
        <table>
            <thead>
            <tr>
                <th>Название</th>
                <th>Статус</th>
                <th>В игре</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($content as $gsId => $row) { ?>
                        <?php if (isset($row['error'])) { ?>
                            <td><?php echo $row['gs']->name ?></td>
                            <td colspan="2"><?php echo $row['error'] ?></td>
                        <?php } else { ?>
                            <?php

                            /**
                             * Кол-во игроков: $row['online']
                             * Название сервера: $row['gs']->name
                             * Ссылка на статистику сервера: url('/stats/default/index', array('gs_id' => $row['gs']->id))
                             */
                            ?>
                            <td><a target="_blank" href="<?php echo app()->createUrl('/stats/default/index', ['gs_id' => $row['gs']->id]) ?>"><?php echo $row['gs']->name ?></a></td>
                            <td>
                                <?php // Статус логин сервера ?>
                                <?php /* ?>
                                <div class="status-<?php echo $row['ls_status'] ?>"></div>
                                <?php */ ?>
                                <?php // Статус игрового сервера ?>
                                <div class="status-<?php echo $row['gs_status'] ?>"></div>
                            </td>
                            <td><?php echo $row['online'] ?></td>
                        <?php } ?>
                    <?php } ?>
                </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">Общий онлайн</td>
                <td><?php echo $totalOnline ?></td>
            </tr>
            </tfoot>
        </table>
    <?php } else { ?>
        Нет данных.
    <?php } ?>
<?php } else { ?>
    Модуль отключен.
<?php } ?>