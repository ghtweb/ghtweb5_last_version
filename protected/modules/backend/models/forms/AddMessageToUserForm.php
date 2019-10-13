<?php

namespace app\modules\backend\models\forms;

class AddMessageToUserForm extends \UserMessages
{
    public function __construct($userId, $scenario = 'insert')
    {
        parent::__construct($scenario);
        $this->user_id = $userId;
    }

    public function rules()
    {
        $rules = [];

        // message
        $rules[] = ['message', 'filter', 'filter' => 'trim'];
        $rules[] = ['message', 'filter', 'filter' => 'strip_tags'];
        $rules[] = ['message', 'required'];
        $rules[] = ['message', 'length', 'min' => 5];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'message' => 'Сообщение',
        ];
    }
}
