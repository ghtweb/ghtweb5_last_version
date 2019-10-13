<?php

namespace app\modules\backend\models\forms;

class BonusesItemsForm extends \BonusesItems
{
    public $item_name;

    public function rules()
    {
        $rules = [];

        // item_name
        $rules[] = ['item_name', 'filter', 'filter' => 'trim'];

        // item_id
        $rules[] = ['item_id', 'filter', 'filter' => 'trim'];
        $rules[] = ['item_id', 'required'];
        $rules[] = ['item_id', 'numerical', 'integerOnly' => true];

        // count
        $rules[] = ['count', 'filter', 'filter' => 'trim'];
        $rules[] = ['count', 'required'];
        $rules[] = ['count', 'numerical', 'integerOnly' => true];

        // enchant
        $rules[] = ['enchant', 'filter', 'filter' => 'trim'];
        $rules[] = ['enchant', 'required'];
        $rules[] = ['enchant', 'numerical', 'integerOnly' => true];

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
            'item_id' => 'ID предмета',
            'count' => 'Количество',
            'enchant' => 'Уровень заточки',
            'status' => 'Статус',
        ];
    }
}
