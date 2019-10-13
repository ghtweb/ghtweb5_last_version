<?php

/**
 * This is the model class for table "{{transactions}}".
 *
 * The followings are the available columns in table '{{transactions}}':
 * @property string $id
 * @property string $payment_system
 * @property string $user_id
 * @property integer $sum
 * @property integer $count
 * @property integer $status
 * @property string $user_ip
 * @property string $params
 * @property integer $gs_id
 * @property string $updated_at
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Users[] $users
 * @property Users $user
 */
class Transactions extends ActiveRecord
{
    // Status
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 0;


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{transactions}}';
    }

    public function relations()
    {
        return [
            'users' => [self::BELONGS_TO, 'Users', 'user_id'],
            'user' => [self::HAS_ONE, 'Users', ['user_id' => 'user_id']],
        ];
    }

    public static function getPayStatusList()
    {
        return [
            self::STATUS_SUCCESS => 'Оплачена',
            self::STATUS_FAILED => 'Не оплачена',
        ];
    }

    public function getStatus()
    {
        $data = self::getPayStatusList();

        return isset($data[$this->status]) ? $data[$this->status] : '*Unknown*';
    }

    public function getAggregatorType()
    {
        Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

        $data = Deposit::getAggregatorsList();

        return isset($data[$this->payment_system]) ? $data[$this->payment_system] : '*Unknown*';
    }

    /**
     * Оплачена ли транзакция
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->status == self::STATUS_SUCCESS;
    }

    public function getType()
    {
        Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

        $data = Deposit::getAggregatorsList();
        return isset($data[$this->payment_system]) ? $data[$this->payment_system] : '*Unknown*';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Transactions::class,
            'idAttributeName' => 'user_id',
        ];

        return $behaviors;
    }
}
