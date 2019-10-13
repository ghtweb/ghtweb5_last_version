<?php

namespace app\modules\backend\models\forms;

class BonusesForm extends \Bonuses
{
    public function rules()
    {
        $rules = [];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];
        $rules[] = ['title', 'required'];
        $rules[] = ['title', 'length', 'max' => 255];

        // date_end
        $rules[] = ['date_end', 'filter', 'filter' => 'trim'];
        $rules[] = ['date_end', 'date', 'allowEmpty' => true, 'format' => 'yyyy-mm-dd HH:mm:ss'];
        $rules[] = ['date_end', 'default', 'value' => null];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'date_end' => 'Дата окончания бонуса',
            'status' => 'Статус',
        ];
    }
}
