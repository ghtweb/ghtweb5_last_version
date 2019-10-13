<?php

namespace app\modules\backend\models\forms;

class GsForm extends \Gs
{
    public $stats_items_list = '57,4037';
    public $currency_name = 'GHTWEB Coin of Luck';
    public $deposit_desc = 'Пожертвование';
    public $port = 7777;
    public $db_host = '127.0.0.1';
    public $db_port = 3306;
    public $stats_items = 0;

    public function rules()
    {
        return [
            ['name, ip, port, db_host, db_port, db_user, db_name, login_id,
                version, status, fake_online, allow_teleport, teleport_time, stats_allow, stats_cache_time, stats_total,
                stats_pvp, stats_pk, stats_clans, stats_castles, stats_online, stats_clan_info, stats_top, stats_rich,
                stats_count_results, exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb, currency_name, 
                deposit_allow, deposit_payment_system, deposit_desc, deposit_course_payments, currency_symbol, 
                stats_items, stats_items_list, online_txt_allow', 'filter', 'filter' => 'trim'],

            ['name, ip, port, db_host, db_port, db_user, db_name, login_id, version, status, fake_online,
                allow_teleport, teleport_time, stats_allow, stats_cache_time, stats_total, stats_pvp, stats_pk,
                stats_clans, stats_castles, stats_online, stats_clan_info, stats_top, stats_rich, stats_count_results,
                exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb, currency_name, deposit_allow, 
                deposit_payment_system, deposit_desc, deposit_course_payments, stats_items, online_txt_allow', 'required'],

            ['deposit_payment_system', 'checkDepositPaymentSystem'],

            ['allow_teleport, stats_allow, stats_total, stats_pvp, stats_pk, stats_clans, stats_castles, stats_online, 
            stats_clan_info, stats_top, stats_rich, deposit_allow, stats_items', 'in', 'range' => array_keys(\ActiveRecord::getStatusListWithoutDelete())],

            ['status', 'in', 'range' => array_keys(parent::getStatusList())],

            ['port, db_port, login_id, fake_online, teleport_time, stats_cache_time, stats_count_results,
                exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb, online_txt_allow', 'numerical', 'integerOnly' => true],

            ['currency_name', 'length', 'max' => 128],
            ['name, ip, db_host, db_user, db_pass, db_name', 'length', 'max' => 54],
            ['port, db_port, fake_online, teleport_time, stats_cache_time, stats_count_results', 'length', 'max' => 11],
            ['exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb', 'length', 'max' => 6],
            ['version', 'length', 'max' => 20],

            ['db_pass', 'default', 'value' => null],

            ['stats_items', 'checkStatsItemsList'],
            ['stats_items_list', 'default', 'value' => null],

            ['login_id', 'in', 'range' => array_keys(\Ls::getOpenLoginServers()), 'message' => 'Выберите логин сервер'],
            ['version', 'in', 'range' => array_keys(serverVersionList()), 'message' => 'Выберите версию сервера'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'ip' => 'IP адрес',
            'port' => 'Порт',
            'db_host' => 'MYSQL host',
            'db_port' => 'MYSQL port',
            'db_user' => 'MYSQL user',
            'db_pass' => 'MYSQL pass',
            'db_name' => 'MYSQL bd name',
            'login_id' => 'Логин',
            'version' => 'Версия сервера',
            'status' => 'Статус',
            'fake_online' => 'Накрутка онлайна',
            'allow_teleport' => 'Телепорт',
            'teleport_time' => 'Время повторного телепорта',
            'stats_allow' => 'Статистика',
            'stats_cache_time' => 'Время кэширования статистики',
            'stats_total' => 'Общая',
            'stats_pvp' => 'Топ пвп',
            'stats_pk' => 'Топ пк',
            'stats_clans' => 'Кланы',
            'stats_castles' => 'Замки',
            'stats_online' => 'В игре',
            'stats_clan_info' => 'Просмотр клана',
            'stats_top' => 'Топ',
            'stats_rich' => 'Богачи',
            'stats_count_results' => 'Кол-во результатов',
            'exp' => 'Exp',
            'sp' => 'Sp',
            'drop' => 'Drop',
            'adena' => 'Adena',
            'items' => 'Items',
            'spoil' => 'Spoil',
            'q_drop' => 'Quest drop',
            'q_reward' => 'Quest reward',
            'rb' => 'Rb',
            'erb' => 'Erb',
            'currency_name' => 'Название игровой валюты',
            'deposit_allow' => 'Разрешить пополнение баланса личного кабинета',
            'deposit_payment_system' => 'Платежная система',
            'deposit_desc' => 'Описание платежа',
            'deposit_course_payments' => 'Курс валют',
            'currency_symbol' => 'Валюта',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'stats_items' => 'Статистика предметов',
            'stats_items_list' => 'Список ID предметов для вывода в статистике',
            'online_txt_allow' => 'Записывать онлайн сервера в файл online.txt',
        ];
    }

    /**
     * Проверка ID предметов
     *
     * @param $attribute
     */
    public function checkStatsItemsList($attribute)
    {
        if ($this->stats_items && $this->stats_items_list == '') {
            $this->addError($attribute, 'Введите ID предметов для вывода в статистике предметов.');
        }
    }

    /**
     * Проверка выбранного агрегатора
     */
    public function checkDepositPaymentSystem()
    {
        \Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

        $data = \Deposit::getAggregatorsList();

        if (!isset($data[$this->deposit_payment_system])) {
            $this->addError('deposit_payment_system', 'Выберите платежную систему');
        }
    }
}
