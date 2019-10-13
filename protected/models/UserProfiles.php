<?php

\Yii::import('ext.MyValidators.ValidIp');

/**
 * This is the model class for table "{{user_profiles}}".
 *
 * The followings are the available columns in table '{{user_profiles}}':
 * @property int $id
 * @property int $user_id
 * @property int $balance
 * @property string $protected_ip
 * @property string $phone
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserProfiles extends ActiveRecord
{
    const DEFAULT_BALANCE = 0; // Дефолтное значение баланса

    public function tableName()
    {
        return '{{user_profiles}}';
    }

    public function rules()
    {
        return [
            ['balance', 'required'],
            ['balance', 'length', 'max' => 10],
            ['protected_ip', 'ValidIp', 'on' => 'security'],
            ['id, user_id, balance', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'user' => [self::HAS_ONE, 'Users', ['user_id' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Юзер',
            'balance' => 'Баланс',
            'protected_ip' => 'IP адрес(а)',
            'phone' => 'Телефон',
        ];
    }

    protected function afterFind()
    {
        if ($this->protected_ip) {
            $this->protected_ip = json_decode($this->protected_ip, true);
        }
        parent::afterFind();
    }

    protected function beforeSave()
    {
        if ($this->protected_ip) {
            $ipList = explode(PHP_EOL, $this->protected_ip);
            $ipList = array_filter($ipList, 'trim');

            $this->protected_ip = json_encode($ipList);
        }
        return parent::beforeSave();
    }

    protected function afterSave()
    {
        if ($this->protected_ip) {
            $this->protected_ip = json_decode($this->protected_ip, true);
        }
        parent::afterSave();
    }
}
