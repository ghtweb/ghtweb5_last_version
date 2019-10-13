<?php

namespace app\modules\backend\models\forms;

class ShopPackItemsForm extends \ShopItems
{
    public $item_name;
    protected $gs;

    /**
     * @return \Gs
     */
    public function getGs()
    {
        return $this->gs;
    }

    /**
     * @param \Gs $gs
     */
    public function setGs($gs)
    {
        $this->gs = $gs;
    }

    public function rules()
    {
        $rules = [];

        // item_name
        $rules[] = ['item_name', 'filter', 'filter' => 'trim'];

        // item_id
        $rules[] = ['item_id', 'filter', 'filter' => 'trim'];
        $rules[] = ['item_id', 'required'];
        $rules[] = ['item_id', 'numerical', 'integerOnly' => true];

        // description
        $rules[] = ['description', 'filter', 'filter' => 'trim'];

        // cost
        $rules[] = ['cost', 'filter', 'filter' => 'trim'];
        $rules[] = ['cost', 'required'];
        $rules[] = ['cost', 'numerical', 'min' => 1];

        // discount
        $rules[] = ['discount', 'filter', 'filter' => 'trim'];
        $rules[] = ['discount', 'required'];
        $rules[] = ['discount', 'numerical', 'integerOnly' => true, 'min' => 0];

        // count
        $rules[] = ['count', 'filter', 'filter' => 'trim'];
        $rules[] = ['count', 'required'];
        $rules[] = ['count', 'numerical', 'integerOnly' => true, 'min' => 1];

        // sort
        $rules[] = ['sort', 'filter', 'filter' => 'trim'];
        $rules[] = ['sort', 'required'];
        $rules[] = ['sort', 'numerical', 'integerOnly' => true, 'min' => 1];

        // enchant
        $rules[] = ['enchant', 'filter', 'filter' => 'trim'];
        $rules[] = ['enchant', 'required'];
        $rules[] = ['enchant', 'numerical', 'integerOnly' => true, 'min' => 0];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->item_name = $this->itemInfo->name;
    }

    public function attributeLabels()
    {
        return [
            'item_name' => 'Название предмета',
            'description' => 'Описание',
            'item_id' => 'ID предмета',
            'cost' => 'Стоймость в ' . $this->gs->currency_name,
            'discount' => 'Скидка',
            'count' => 'Кол-во',
            'enchant' => 'Заточка',
            'sort' => 'Сортировка',
            'status' => 'Статус',
            'img' => 'Картинка',
        ];
    }


}
