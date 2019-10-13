<?php

namespace app\modules\backend\models\search;

class UsersAuthLogsSearch extends \UsersAuthLogs
{
    public function rules()
    {
        $rules = [];

        // id
        $rules[] = ['id', 'numerical', 'integerOnly' => true];

        // ip
        $rules[] = ['code', 'filter', 'filter' => 'trim'];

        // user_agent
        $rules[] = ['user_agent', 'filter', 'filter' => 'trim'];

        return $rules;
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('id', $this->id);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('user_agent', $this->user_agent, true);

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
