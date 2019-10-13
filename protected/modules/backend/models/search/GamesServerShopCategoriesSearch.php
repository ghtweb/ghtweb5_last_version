<?php

namespace app\modules\backend\models\search;

class GamesServerShopCategoriesSearch extends \ShopCategories
{
    public function search()
    {
        $criteria = new \CDbCriteria;
        $criteria->order = 'created_at desc';
        $criteria->condition = 'gs_id = :gs_id';
        $criteria->params['gs_id'] = $this->gs_id;

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
