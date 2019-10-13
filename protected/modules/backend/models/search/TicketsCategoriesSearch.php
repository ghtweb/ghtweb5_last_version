<?php

namespace app\modules\backend\models\search;

class TicketsCategoriesSearch extends \TicketsCategories
{
    public function search()
    {
        $criteria = new \CDbCriteria;
        $criteria->order = 'sort';

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
