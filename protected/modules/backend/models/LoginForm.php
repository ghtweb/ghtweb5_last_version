<?php

class LoginForm extends Admin
{
    public function rules()
    {
        return [
            ['login, password', 'filter', 'filter' => 'trim'],
            ['login, password', 'required'],
            ['login', 'length', 'max' => Users::LOGIN_MAX_LENGTH, 'min' => Users::LOGIN_MIN_LENGTH],
            ['password', 'length', 'max' => Users::PASSWORD_MAX_LENGTH, 'min' => Users::PASSWORD_MIN_LENGTH],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    public function login()
    {
        $login = $this->login;
        $password = $this->password;
        $identity = new AdminIdentity($login, $password, new \app\services\YiiPasswordManager());
        $identity->authenticate();

        switch ($identity->errorCode) {
            case AdminIdentity::ERROR_USERNAME_INVALID:
            case AdminIdentity::ERROR_PASSWORD_INVALID:
                $this->addError('login', 'Аккаунт не найден.');
                break;
            case AdminIdentity::ERROR_STATUS_BANNED:
                $this->addError('login', 'Аккаунт забанен.');
                break;
            case AdminIdentity::ERROR_STATUS_INACTIVE:
                $this->addError('login', 'Аккаунт не активирован.');
                break;
            case AdminIdentity::ERROR_STATUS_IP_NO_ACCESS:
                $this->addError('login', 'Доступ к аккаунту для вашего IP запрещён.');
                break;
            default:
                $duration = 3600 * 24 * 7; // 7 days
                admin()->login($identity, $duration);

                return true;
        }

        return false;
    }
}
 