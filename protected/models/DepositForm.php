<?php

class DepositForm extends CFormModel
{
    public $sum = 1;


    public function rules()
    {
        return [
            ['sum', 'filter', 'filter' => 'trim'],
            ['sum', 'required'],
            ['sum', 'numerical', 'integerOnly' => true, 'min' => 1, 'message' => 'Введите число', 'tooSmall' => 'Кол-во должно быть больше нуля'],
        ];
    }

    /**
     * Валидация суммы
     *
     * @param $attributes
     * @param $params
     *
     * @return bool
     */
    public function validCount($attributes, $params)
    {
        if (!is_numeric($this->sum)) {
            $this->addError(__FUNCTION__, 'Сумма должна быть числом.');

            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'sum' => 'Кол-во ' . app()->controller->gs->currency_name,
        ];
    }
}
 