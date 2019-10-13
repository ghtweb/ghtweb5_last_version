<?php

namespace app\modules\backend\models\search;

class GallerySearch extends \Gallery
{
    public function search()
    {
        $criteria = new \CDbCriteria;
        $criteria->order = 'sort';

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 't.created_at DESC'
            ],
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
