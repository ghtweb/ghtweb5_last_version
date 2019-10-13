<?php

namespace app\modules\backend\models\search;

class TransactionsSearch extends \Transactions
{
    public function rules()
    {
        $rules = [];

        // id
        $rules[] = ['id', 'numerical', 'integerOnly' => true];

        // payment_system
        $rules[] = ['payment_system', 'in', 'range' => array_keys($this->getAggregatorsList())];

        // sum
        $rules[] = ['sum', 'numerical', 'integerOnly' => true];

        // status
        $rules[] = ['status', 'in', 'range' => array_keys(self::getPayStatusList())];

        // user_ip
        $rules[] = ['user_ip', 'filter', 'filter' => 'trim'];

        return $rules;
    }

    /**
     * @return array
     * @throws \CException
     */
    public function getAggregatorsList()
    {
        \Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

        return \Deposit::getAggregatorsList();
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.payment_system', $this->payment_system, true);
        $criteria->compare('t.user_ip', $this->user_ip, true);
        $criteria->compare('t.sum', $this->sum, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.created_at', $this->created_at, true);

        $criteria->with = ['user'];

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 't.status DESC, t.created_at DESC'
            ],
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
