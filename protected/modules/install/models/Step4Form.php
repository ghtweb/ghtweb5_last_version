<?php

use app\services\PasswordManagerInterface;

/**
 * Class Step4Form
 *
 * @property string $login
 * @property string $password
 */
class Step4Form extends CFormModel
{
    /**
     * Название сервера
     * @var string
     */
    public $login;

    /**
     * Пароль
     * @var string
     */
    public $password;
    private $passwordManager;

    public function __construct(PasswordManagerInterface $passwordManager, string $scenario = '')
    {
        parent::__construct($scenario);
        $this->passwordManager = $passwordManager;
    }

    public function rules()
    {
        return [
            ['login, password', 'filter', 'filter' => 'trim'],
            ['login, password', 'required'],
            ['login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH],
            ['password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH],
            ['login', 'loginUnique'],
        ];
    }

    public function loginUnique($attr)
    {
        if (!$this->hasErrors($attr)) {
            $isExists = Admin::model()->exists('login = :login', ['login' => $this->login]);

            if ($isExists) {
                $this->addError($attr, 'Логин уже занят.');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    public function createAdmin()
    {
        if (!$this->validate()) {
            return false;
        }
        $admin = Admin::create($this->login, $this->passwordManager->encode($this->password));
        if (!$admin->save(false)) {
            $this->addError('login', 'Не удалось создать админа!');
        }
        return true;
    }
}
