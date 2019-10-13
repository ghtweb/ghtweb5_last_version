<?php

namespace app\modules\backend\models\search;

class BonusesItemsSearch extends \BonusesItems
{
    public function rules()
    {
        $rules = [];

        return $rules;
    }

    public function search($bonusId)
    {
        $criteria = new \CDbCriteria;

        $criteria->condition = 'bonus_id = ?';
        $criteria->params[] = $bonusId;

        $criteria->with = ['itemInfo' => [
            'order' => 'name'
        ]];

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
