<?php

namespace app\modules\backend\models\search;

class AdminSearch extends \Admin
{
    public function rules()
    {
        $rules = [];

        // id
        $rules[] = ['id', 'numerical', 'integerOnly' => true];

        // login
        $rules[] = ['login', 'filter', 'filter' => 'trim'];

        return $rules;
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('login', $this->login, true);

        $criteria->order = 't.created_at DESC';

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 15,
                'pageVar' => 'page',
            ],
        ]);
    }
}
