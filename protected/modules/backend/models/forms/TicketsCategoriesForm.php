<?php

namespace app\modules\backend\models\forms;

class TicketsCategoriesForm extends \TicketsCategories
{
    public function rules()
    {
        $rules = [];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];
        $rules[] = ['title', 'required'];
        $rules[] = ['title', 'length', 'max' => 255];
        $rules[] = ['title', 'unique', 'message' => 'Категория уже существует, впишите другое название.'];

        // sort
        $rules[] = ['sort', 'filter', 'filter' => 'trim'];
        $rules[] = ['sort', 'required'];
        $rules[] = ['sort', 'numerical', 'integerOnly' => true];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    public function init()
    {
        parent::init();

        if ($this->isNewRecord === true) {
            $criteria = new \CDbCriteria();
            $criteria->select = 'MAX(sort) + 1 as sort';
            $res = \TicketsCategories::model()->find($criteria);
            $this->sort = isset($res->sort) ? $res->sort : 1;
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'sort' => 'Сортировка',
            'status' => 'Статус',
        ];
    }
}
