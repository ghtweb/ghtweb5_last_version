<?php

namespace app\modules\backend\models\search;

class GamesServerShopCategoriesPacksSearch extends \ShopItemsPacks
{
    public function search($categoryId)
    {
        $criteria = new \CDbCriteria;
        $criteria->condition = 'category_id = ?';
        $criteria->params = [$categoryId];
        $criteria->order = 'created_at desc';
        $criteria->with = 'countItems';

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
