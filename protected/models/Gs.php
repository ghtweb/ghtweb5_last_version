<?php

/**
 * This is the model class for table "{{gs}}".
 *
 * The followings are the available columns in table '{{gs}}':
 * @property integer $id
 * @property string $name
 * @property string $ip
 * @property string $port
 * @property string $db_host
 * @property string $db_port
 * @property string $db_user
 * @property string $db_pass
 * @property string $db_name
 * @property integer $login_id
 * @property string $version
 * @property string $fake_online
 * @property integer $allow_teleport
 * @property string $teleport_time
 * @property integer $stats_allow
 * @property string $stats_cache_time
 * @property integer $stats_total
 * @property integer $stats_pvp
 * @property integer $stats_pk
 * @property integer $stats_clans
 * @property integer $stats_castles
 * @property integer $stats_online
 * @property integer $stats_clan_info
 * @property integer $stats_top
 * @property integer $stats_rich
 * @property string $stats_count_results
 * @property string $exp
 * @property string $sp
 * @property string $adena
 * @property string $drop
 * @property string $items
 * @property string $spoil
 * @property string $q_drop
 * @property string $q_reward
 * @property string $rb
 * @property string $erb
 * @property string $currency_name
 * @property integer $deposit_allow
 * @property integer $deposit_payment_system
 * @property string $deposit_desc
 * @property integer $deposit_course_payments
 * @property integer $currency_symbol
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $stats_items
 * @property string $stats_items_list
 * @property int $online_txt_allow
 * @property Ls $ls
 */
class Gs extends ActiveRecord
{
    public function tableName()
    {
        return '{{gs}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Gs::class,
        ];

        return $behaviors;
    }

    public function relations()
    {
        return [
            'ls' => [self::HAS_ONE, 'Ls', ['id' => 'login_id']],
        ];
    }

    /**
     * Возвращает название валюты за которую покупают игровую валюту
     *
     * @return string
     */
    public function getCurrencySymbolName()
    {
        return app()->locale->getCurrencySymbol($this->currency_symbol);
    }

    /**
     * Список платёжных систем
     *
     * @return array
     */
    public function getAggregatorsList()
    {
        $data = Deposit::getAggregatorsList();
        unset($data[Deposit::PAYMENT_SYSTEM_UNITPAY_SMS], $data[Deposit::PAYMENT_SYSTEM_WAYTOPAY_SMS]);

        return $data;
    }

    /**
     * Возвращает список открытых серверов
     *
     * @return Gs[]
     */
    public static function getOpenServers()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class),
        ]);
        $list = [];

        /** @var Gs[] $res */
        $res = Gs::model()->cache(3600, $dependency)->opened()->findAll();

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }

    /**
     * Все игровые сервера
     *
     * @return Gs[]
     */
    public static function getAllServers()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class),
        ]);
        $list = [];

        /** @var Gs[] $res */
        $res = Gs::model()->cache(3600, $dependency)->findAll();

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }
}
