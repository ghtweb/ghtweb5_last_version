<?php

namespace app\modules\backend\models\forms;

class ShopItemsPackForm extends \ShopItemsPacks
{
    public function setMaxSort()
    {
        if ($this->getIsNewRecord()) {
            $criteria = new \CDbCriteria();
            $criteria->select = 'MAX(sort) + 1 as sort';
            $criteria->condition = 'category_id = ?';
            $criteria->params = [$this->category_id];
            $res = \ShopItemsPacks::model()->find($criteria);
            $this->sort = isset($res->sort) ? $res->sort : 1;
        }
    }

    public function rules()
    {
        $rules = [];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];
        $rules[] = ['title', 'required'];
        $rules[] = ['title', 'length', 'max' => 255];

        // description
        $rules[] = ['description', 'filter', 'filter' => 'trim'];

        // sort
        $rules[] = ['sort', 'filter', 'filter' => 'trim'];
        $rules[] = ['sort', 'required'];
        $rules[] = ['sort', 'numerical', 'integerOnly' => true];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        // upload_img
        $rules[] = ['img', 'file', 'types' => 'jpg, jpeg, png', 'allowEmpty' => true];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'description' => 'Описание',
            'sort' => 'Сортировка',
            'status' => 'Статус',
            'img' => 'Картинка',
        ];
    }
}
