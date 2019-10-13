<?php

namespace app\modules\backend\models\forms;

use app\services\YiiPasswordManager;

class AdminEditForm extends \CFormModel
{
    public $password;
    private $admin;

    public function __construct(\Admin $admin, string $scenario = '')
    {
        parent::__construct($scenario);
        $this->admin = $admin;
    }

    public function rules()
    {
        $rules = [];

        // password
        $rules[] = ['password', 'filter', 'filter' => 'trim'];
        $rules[] = ['password', 'required'];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return false;
        }
        $passwordManager = new YiiPasswordManager();
        $this->admin->setPassword($passwordManager->encode($this->password));
        if (!$this->admin->save(false)) {
            $this->addError('login', 'Не удалось сохранить новые данные');
        }
        return true;
    }
}
