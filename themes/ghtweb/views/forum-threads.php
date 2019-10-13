<?php if (!empty($error)) { ?>
    <?php echo $error ?>
<?php } else { ?>

    <ul class="list-unstyled">
        <?php foreach ($content as $row) { ?>
            <li>
                <a class="title" href="<?php echo $row['theme_link'] ?>"
                   target="_blank"><?php echo characterLimiter($row['title'], config('forum_threads.characters_limit')) ?></a>
                <span>Автор:</span> <a href="<?php echo $row['user_link'] ?>"
                                       target="_blank"><?php echo e($row['starter_name']) ?></a>
                <span class="date"><span>Дата: </span><?php echo $row['start_date'] ?></span><br>
            </li>
        <?php } ?>
    </ul>

<?php } ?>