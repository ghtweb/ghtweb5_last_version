<?php

namespace app\modules\backend\models\forms;

use Cocur\Slugify\Slugify;
use Stichoza\GoogleTranslate\TranslateClient;

class ShopCategoryForm extends \ShopCategories
{
    public function init()
    {
        $this->attachEventHandler('onBeforeSave', [$this, 'generateSlug']);

        if ($this->getIsNewRecord()) {
            $criteria = new \CDbCriteria();
            $criteria->select = 'MAX(sort) + 1 as sort';
            $res = \ShopCategories::model()->find($criteria);
            $this->sort = isset($res->sort) ? $res->sort : 1;
        }
    }

    public function rules()
    {
        $rules = [];

        // name
        $rules[] = ['name', 'filter', 'filter' => 'trim'];
        $rules[] = ['name', 'required'];
        $rules[] = ['name', 'length', 'max' => 255];
        $rules[] = ['name', 'unique',
            'criteria' => [
                'condition' => 'gs_id = :gs_id',
                'params' => ['gs_id' => $this->gs_id],
            ],
            'message' => 'Название "{value}" уже занято'];

        // sort
        $rules[] = ['sort', 'filter', 'filter' => 'trim'];
        $rules[] = ['sort', 'required'];
        $rules[] = ['sort', 'numerical', 'integerOnly' => true];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название категории',
            'sort' => 'Сортировка',
            'status' => 'Статус',
        ];
    }

    public function generateSlug(\CEvent $event)
    {
        $tr = new TranslateClient('ru');
        $name = $tr->translate($event->sender->name);

        $event->sender->link = substr((new Slugify())->slugify($name), 0, 255);
    }
}
