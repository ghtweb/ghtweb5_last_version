<?php

namespace app\modules\cabinet\models\forms;

class ShopForm extends \CFormModel
{
    public function rules()
    {
        $rules = [];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            ''
        ];
    }

    public function buy()
    {
        if (!$this->hasErrors()) {
            return false;
        }

        prt($this->attributes);
    }
}
