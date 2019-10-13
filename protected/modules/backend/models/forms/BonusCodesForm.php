<?php

namespace app\modules\backend\models\forms;

class BonusCodesForm extends \BonusCodes
{
    public function rules()
    {
        $rules = [];

        // code
        $rules[] = ['code', 'filter', 'filter' => 'trim'];
        $rules[] = ['code', 'required'];
        $rules[] = ['code', 'length', 'max' => 128];

        // limit
        $rules[] = ['limit', 'filter', 'filter' => 'trim'];
        $rules[] = ['limit', 'required'];

        // bonus_id
        $rules[] = ['bonus_id', 'filter', 'filter' => 'trim'];
        $rules[] = ['bonus_id', 'required'];
        $rules[] = ['bonus_id', 'checkBonus'];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    public function checkBonus()
    {
        if (!$this->hasErrors()) {
            $model = \Bonuses::model()->findByPk($this->bonus_id);
            if (is_null($model)) {
                $this->addError('checkBonus', 'Выберите бонус.');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'code' => 'Код',
            'bonus_id' => 'Бонус',
            'limit' => 'Лимит',
            'status' => 'Статус',
        ];
    }
}
