<?php

$params = [

    // $_GET параметр отвечающий за реферала
    'cookie_referer_name' => 'ref_id',

    // Папка куда кидаются картинки
    'uploadPath' => 'uploads',

    // Логирование действий юзера (лучше не включать, создаётся доп. нагрузка на БД)
    'user_actions_log' => false,

    // Типы форумов
    'forum_types' => ['ipb', 'phpbb', 'smf', 'vanilla', 'vBulletin', 'xenForo'],

    'l2' => require __DIR__ . '/lineage.php',

    // ID супер админов которые могут создавать новых админов
    'superAdminId' => [1],
];

return $params;
