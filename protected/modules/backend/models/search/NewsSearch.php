<?php

namespace app\modules\backend\models\search;

class NewsSearch extends \News
{
    public function rules()
    {
        $rules = [];

        // id
        $rules[] = ['id', 'numerical', 'integerOnly' => true];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];

        // status
        $rules[] = ['status', 'in', 'range' => array_keys(\ActiveRecord::getStatusListWithoutDelete())];

        return $rules;
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('status', $this->status);

        $criteria->with = ['author'];

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
