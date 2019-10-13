<?php

class m140730_183829_seed_config_table extends CDbMigration
{
    public function safeUp()
    {
        $this->insert('{{config_group}}', [
            'name' => 'Основные',
            'order' => 1,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = [
            ['theme', '', '', 'Тема сайта', $insertId, 1, 'getThemes', 'dropDownList'],
            ['meta.title', 'GHTWEB CMS для Lineage2 серверов', 'GHTWEB CMS для Lineage2 серверов', 'Название сайта, используется в <title> и письмах', $insertId, 2, '', 'textField'],
            ['meta.description', 'GHTWEB CMS для Lineage2 серверов', 'GHTWEB CMS для Lineage2 серверов', 'Описание сайта, используется в <meta name="description">', $insertId, 3, '', 'textField'],
            ['meta.keywords', 'GHTWEB CMS для Lineage2 серверов', 'GHTWEB CMS для Lineage2 серверов', 'Ключевые слова сайта, используется в <meta name="keywords">', $insertId, 4, '', 'textField'],
            ['meta.title_divider', ' - ', ' - ', 'Разделить в <title>', $insertId, 5, '', 'textField'],
            ['index.type', 'page', 'page', 'Что выводить на главной странице сайта', $insertId, 6, 'getIndexPageTypes', 'dropDownList'],
            ['index.page', 'main', 'main', 'Страница для главной страницы (если выбран вывод на главную "Страница")', $insertId, 7, 'getPages', 'dropDownList'],
            ['index.rss.url', '', '', 'RSS новости: Ссылка на RSS с которого будут браться данные', $insertId, 8, '', 'textField'],
            ['index.rss.date_format', 'Y-m-d H:i', 'Y-m-d H:i', 'RSS новости: Формат даты', $insertId, 9, '', 'textField'],
            ['index.rss.cache', '15', '15', 'RSS новости: Через сколько минут обновлять кэш', $insertId, 10, '', 'textField'],
            ['index.rss.limit', '5', '5', 'RSS новости: Записей на страницу', $insertId, 11, '', 'textField'],
        ];

        $this->insert('{{config_group}}', [
            'name' => 'Регистрация',
            'order' => 2,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['register.captcha.allow', 1, 0, 'Включить капчу', $insertId, 1, '', 'dropDownList'],
            ['register.allow', 1, 1, 'Разрешить регистрацию новых пользователей', $insertId, 2, '', 'dropDownList'],
            ['register.confirm_email', 1, 1, 'Подтверждение регистрации по Email', $insertId, 3, '', 'dropDownList'],
            ['register.confirm_email.time', 180, 180, 'Время жизни ключа для активации аккаунта (в минутах)', $insertId, 4, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Новости',
            'order' => 3,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['news.per_page', 10, 10, 'Новостей на странице', $insertId, 1, '', 'textField'],
            ['news.date_format', 'Y-m-d H:i', 'Y-m-d H:i', 'Формат даты когда была создана новость, инфа - http://php.net/manual/ru/function.date.php', $insertId, 2, '', 'textField'],
            ['news.detail.socials', 1, 0, 'Включить виджет социальных иконок при просмотре новости, инфа - http://share42.com/', $insertId, 3, '', 'dropDownList'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Email',
            'order' => 4,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['mail.smtp', 0, 0, 'Использовать SMTP', $insertId, 1, '', 'dropDownList'],
            ['mail.smtp_login', '', '', 'SMTP: логин', $insertId, 2, '', 'textField'],
            ['mail.smtp_password', '', '', 'SMTP: пароль', $insertId, 3, '', 'textField'],
            ['mail.admin_email', 'no-reply@admin.ru', 'no-reply@admin.ru', 'Email Администратора', $insertId, 4, '', 'textField'],
            ['mail.smtp_host', '', '', 'SMTP: хост/ip', $insertId, 5, '', 'textField'],
            ['mail.admin_name', 'GHTWEB', 'Вася Пупкин', 'Имя Администратора (подставляется в поле "От кого")', $insertId, 6, '', 'textField'],
            ['mail.smtp_port', 465, 465, 'SMTP: порт', $insertId, 7, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Восстановление пароля',
            'order' => 5,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['forgotten_password.captcha.allow', 1, 1, 'Включить капчу', $insertId, 1, '', 'dropDownList'],
            ['forgotten_password.cache_time', 60, 60, 'Время жизни ключа для восстановления пароля, в минутах', $insertId, 2, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Капча',
            'order' => 6,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['captcha.min_length', 3, 3, 'Мин. кол-во символов', $insertId, 1, '', 'textField'],
            ['captcha.max_length', 6, 6, 'Макс. кол-во символов', $insertId, 2, '', 'textField'],
            ['captcha.width', 95, 95, 'Ширина капчи', $insertId, 3, '', 'textField'],
            ['captcha.height', 32, 32, 'Высота капчи', $insertId, 4, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Виджет: Темы с форума',
            'order' => 7,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['forum_threads.allow', 0, 0, 'Включить вывод тем с форума', $insertId, 1, '', 'dropDownList'],
            ['forum_threads.cache', 15, 15, 'Время кэширования, в минутах. 0 - не кэшировать', $insertId, 2, '', 'textField'],
            ['forum_threads.limit', 4, 10, 'Сколько тем выводить', $insertId, 3, '', 'textField'],
            ['forum_threads.db_host', '127.0.0.1', '127.0.0.1', 'DB host', $insertId, 4, '', 'textField'],
            ['forum_threads.db_port', 3306, 3306, 'DB port', $insertId, 5, '', 'textField'],
            ['forum_threads.db_user', 'root', 'root', 'DB user', $insertId, 6, '', 'textField'],
            ['forum_threads.db_pass', '', '', 'DB pass', $insertId, 7, '', 'textField'],
            ['forum_threads.db_name', '', '', 'DB name', $insertId, 8, '', 'textField'],
            ['forum_threads.type', '', '', 'Тип форума', $insertId, 9, 'getForumTypes', 'dropDownList'],
            ['forum_threads.prefix', '', '', 'Префикс у таблиц', $insertId, 10, '', 'textField'],
            ['forum_threads.date_format', 'Y:m:d H:i', 'Y:m:d H:i', 'Формат даты', $insertId, 11, '', 'textField'],
            ['forum_threads.link', 'http://forum.ghtweb.ru', 'http://forum.ghtweb.ru', 'Ссылка на форум', $insertId, 12, '', 'textField'],
            ['forum_threads.id_deny', '14, 28, 13', '14, 28, 13', 'ID форумов которые запрещены к выводу, через запятую', $insertId, 13, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Платежная система: Robokassa',
            'order' => 8,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['robokassa.test', 1, 1, 'Робокасса в тестовом режиме', $insertId, 1, '', 'dropDownList'],
            ['robokassa.login', '', '', 'Ваш логин', $insertId, 2, '', 'textField'],
            ['robokassa.password', '', '', 'Пароль1', $insertId, 3, '', 'passwordField'],
            ['robokassa.password2', '', '', 'Пароль2', $insertId, 4, '', 'passwordField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Платежная система: Unitpay',
            'order' => 9,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['unitpay.secret_key', '', '', 'Секретный ключ', $insertId, 1, '', 'textField'],
            ['unitpay.project_id', '', '', 'ID проекта', $insertId, 2, '', 'textField'],
            ['unitpay.public_key', '', '', 'Берутся из конца URL на странице с примерами', $insertId, 3, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Платежная система: Waytopay',
            'order' => 9,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['waytopay.project_id', '', '', 'ID проекта', $insertId, 1, '', 'textField'],
            ['waytopay.key', '', '', 'Ключ', $insertId, 2, '', 'textField'],
            ['waytopay.sms.allow', 0, 0, 'SMS включить сервис', $insertId, 3, '', 'textField'],
            ['waytopay.sms.key', '', '', 'SMS ключ', $insertId, 4, '', 'textField'],
            ['waytopay.sms.project_id', '', '', 'SMS ID проекта', $insertId, 5, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Виджет: Статус сервера',
            'order' => 10,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['server_status.allow', 1, 1, 'Включить показ статуса сервера(ов)', $insertId, 1, '', 'dropDownList'],
            ['server_status.cache', 15, 15, 'Через сколько минут обновлять данные', $insertId, 2, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Виджет: Топ ПК',
            'order' => 11,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['top.pk.allow', 0, 0, 'Включить виджет топ пк', $insertId, 1, '', 'dropDownList'],
            ['top.pk.gs_id', 0, 0, 'Сервер с которого брать данные', $insertId, 2, 'getGs', 'dropDownList'],
            ['top.pk.limit', 10, 10, 'Сколько игроков выводить', $insertId, 3, '', 'textField'],
            ['top.pk.cache', 15, 15, 'Через сколько минут обновлять данные', $insertId, 4, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Виджет: Топ ПВП',
            'order' => 12,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['top.pvp.allow', 0, 0, 'Включить виджет топ пвп', $insertId, 1, '', 'dropDownList'],
            ['top.pvp.gs_id', 0, 0, 'Сервер с которого брать данные', $insertId, 2, 'getGs', 'dropDownList'],
            ['top.pvp.limit', 10, 10, 'Сколько игроков выводить', $insertId, 3, '', 'textField'],
            ['top.pvp.cache', 15, 15, 'Через сколько минут обновлять данные', $insertId, 4, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Авторизация',
            'order' => 13,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['login.captcha.allow', 1, 1, 'Включить капчу', $insertId, 1, '', 'dropDownList'],
            ['login.count_failed_attempts_for_blocked', 3, 3, 'Через сколько неудачных попыток авторизоваться юзер будет заблокирован', $insertId, 2, '', 'textField'],
            ['login.failed_attempts_blocked_time', 30, 30, 'На сколько минут блокировать юзера', $insertId, 3, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Реферальная программа',
            'order' => 14,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['referral_program.allow', 1, 1, 'Вкл/Выкл программу пригласи друга (реферальная программа)', $insertId, 1, '', 'dropDownList'],
            ['referral_program.percent', 5, 5, 'Сколько % получит игрок от пожертвований за то что привел друга', $insertId, 2, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Личный кабинет',
            'order' => 15,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['cabinet.referals.limit', 20, 20, 'Кол-во записей с рефералами на страницу', $insertId, 1, '', 'textField'],
            ['cabinet.transaction_history.limit', 20, 20, 'Кол-во записей на странице история пополнений', $insertId, 2, '', 'textField'],
            ['cabinet.auth_logs_limit', 15, 15, 'Кол-во записей на странице история авторизаций', $insertId, 3, '', 'textField'],
            ['cabinet.change_password.captcha.allow', 0, 0, 'Капча при смене пароля от аккаунта', $insertId, 4, '', 'dropDownList'],
            ['cabinet.user_messages_limit', 10, 10, 'Кол-во сообщения на странице личные сообщения', $insertId, 5, '', 'textField'],
            ['cabinet.tickets.limit', 20, 20, 'Кол-во записей на странице  поддержка', $insertId, 6, '', 'textField'],
            ['cabinet.bonuses.limit', 10, 10, 'Кол-во записей на странице бонусы', $insertId, 7, '', 'textField'],
            ['cabinet.tickets.answers.limit', 20, 20, 'Кол-во записей на странице ответы на тикеты', $insertId, 8, '', 'textField'],
            ['shop.item.limit', 5, 5, 'Сколько наборов выводить на страницу в магазине', $insertId, 9, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Галерея',
            'order' => 16,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['gallery.limit', 20, 20, 'Кол-во картинок на страницу', $insertId, 1, '', 'textField'],
            ['gallery.big.width', 800, 800, 'Ширина большой фотки', $insertId, 2, '', 'textField'],
            ['gallery.big.height', 800, 800, 'Высота большой фотки', $insertId, 3, '', 'textField'],
            ['gallery.small.width', 150, 150, 'Ширина превьюшки', $insertId, 4, '', 'textField'],
            ['gallery.small.height', 150, 150, 'Высота превьюшки', $insertId, 5, '', 'textField'],
        ]);

        $this->insert('{{config_group}}', [
            'name' => 'Префиксы',
            'order' => 17,
            'status' => 1,
        ]);

        $insertId = $this->getDbConnection()->getLastInsertID();

        $insertData = array_merge($insertData, [
            ['prefixes.allow', 1, 1, 'Вкл/Выкл префиксы', $insertId, 1, '', 'dropDownList'],
            ['prefixes.length', 3, 3, 'Кол-во символов в префиксе', $insertId, 2, '', 'textField'],
            ['prefixes.count_for_list', 6, 6, 'Кол-во префиксов в выпадающем списке для выбора юзером', $insertId, 3, '', 'textField'],
        ]);


        foreach ($insertData as $k => $v) {
            $insertData[$k] = [
                'param' => $v[0],
                'value' => $v[1],
                'default' => $v[2],
                'label' => $v[3],
                'group_id' => $v[4],
                'order' => $v[5],
                'method' => $v[6],
                'field_type' => $v[7],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $builder = $this->getDbConnection()->getSchema()->getCommandBuilder();
        $builder->createMultipleInsertCommand('{{config}}', $insertData)
            ->execute();
    }

    public function safeDown()
    {
        $this->delete('{{config_group}}');
        $this->delete('{{config}}');
    }
}