<?php

namespace app\modules\backend\models\search;

class GamesServerShopCategoriesPacksItemsSearch extends \ShopItems
{
    public function search($packId)
    {
        $criteria = new \CDbCriteria;
        $criteria->condition = 'pack_id = ?';
        $criteria->params = [$packId];
        $criteria->order = 'created_at desc';
        $criteria->with = 'itemInfo';

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
