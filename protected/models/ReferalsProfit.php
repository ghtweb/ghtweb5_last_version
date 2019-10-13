<?php

/**
 * This is the model class for table "{{referals_profit}}".
 *
 * The followings are the available columns in table '{{referals_profit}}':
 * @property string $id
 * @property string $referer_id
 * @property string $referal_id
 * @property double $profit
 * @property double $sum
 * @property double $percent
 * @property double $transaction_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class ReferalsProfit extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{referals_profit}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['referer_id, referal_id, profit, sum, percent, created_at', 'required'],
            ['profit, sum, percent', 'numerical'],
            ['referer_id, referal_id', 'length', 'max' => 10],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, referer_id, referal_id, profit, sum, percent, created_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'user' => [self::HAS_ONE, 'Users', ['user_id']],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'referer_id' => 'ID юзера который привёл',
            'referal_id' => 'ID юзера кто совершил сделку',
            'profit' => 'Прибыль, % от суммы пополнения',
            'sum' => 'На какую сумму совершен платеж',
            'percent' => '% который был на момент совершения платежа',
            'created_at' => 'Created At',
        ];
    }
}
