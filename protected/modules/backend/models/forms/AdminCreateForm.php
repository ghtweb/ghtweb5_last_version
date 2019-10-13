<?php

namespace app\modules\backend\models\forms;

use app\services\YiiPasswordManager;

class AdminCreateForm extends \CFormModel
{
    public $login;
    public $password;

    public function rules()
    {
        $rules = [];

        // login
        $rules[] = ['login', 'filter', 'filter' => 'trim'];
        $rules[] = ['login', 'required'];
        $rules[] = ['login', 'length', 'max' => \Users::LOGIN_MAX_LENGTH, 'min' => \Users::LOGIN_MIN_LENGTH];
        $rules[] = ['login', 'unique', 'className' => \Admin::class];

        // password
        $rules[] = ['password', 'filter', 'filter' => 'trim'];
        $rules[] = ['password', 'required'];
        $rules[] = ['login', 'length', 'max' => \Users::PASSWORD_MAX_LENGTH, 'min' => \Users::PASSWORD_MIN_LENGTH];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }
        $passwordManager = new YiiPasswordManager();
        $model = \Admin::create($this->login, $passwordManager->encode($this->password));
        if (!$model->save(false)) {
            $this->addError('login', 'Не удалось создать нового админа');
        }
        return true;
    }
}
