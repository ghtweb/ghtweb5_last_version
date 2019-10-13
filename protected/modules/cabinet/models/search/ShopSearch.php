<?php

namespace app\modules\cabinet\models\search;

class ShopSearch extends \ShopCategories
{
    public function search($categoryLink, $gsId)
    {
        $category = $this->getCategoryId($categoryLink, $gsId);

        if (!$category) {
            return false;
        }

        $criteria = new \CDbCriteria();
        $criteria->condition = 'category_id = :category_id';
        $criteria->params = ['category_id' => $category->id];
        $criteria->scopes = ['opened'];
        $criteria->with = ['items' => [
            'scopes' => ['opened'],
            'with' => ['itemInfo'],
        ]];

        $dataProvider =  new \CActiveDataProvider(\ShopItemsPacks::class, [
            'criteria' => $criteria,
            'pagination' => [
                'pageVar' => 'page',
                'pageSize' => 5,
            ],
        ]);

        return [
            'dataProvider' => $dataProvider,
            'category' => $category,
        ];
    }

    private function getCategoryId($categoryLink, $gsId)
    {
        $categories = self::getOpenCategories($gsId);

        if (is_null($categoryLink)) {
            $firstCategory = reset($categories);
            $category = $firstCategory;
        } else {
            $category = null;
            foreach ($categories as $item) {
                if ($item->link == $categoryLink) {
                    $category = $item;
                    break;
                }
            }
        }

        return $category;
    }
}
