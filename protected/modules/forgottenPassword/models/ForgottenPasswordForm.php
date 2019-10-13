<?php

/**
 * Class ForgottenPasswordForm
 *
 * @property array $gs_list
 * @property int $gs_id
 * @property string $login
 * @property string $email
 * @property string $verifyCode
 */
class ForgottenPasswordForm extends CFormModel
{
    /**
     * @var Gs[]
     */
    public $gs_list;

    /**
     * @var int
     */
    public $gs_id;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $verifyCode;

    /**
     * @var Users
     */
    private $user;


    public function rules()
    {
        $rules = [
            ['gs_id,login,email,verifyCode', 'filter', 'filter' => 'trim'],
            ['gs_id,login,email', 'required'],
            ['login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH],
            ['email', 'email', 'message' => 'Введите корректный Email адрес.'],
            ['login', 'loginIsExists'],
            ['gs_id', 'gsIsExists'],
        ];

        // Captcha
        $captcha = config('forgotten_password.captcha.allow') && CCaptcha::checkRequirements();

        if ($captcha) {
            $rules[] = ['verifyCode', 'filter', 'filter' => 'trim'];
            $rules[] = ['verifyCode', 'required'];
            $rules[] = ['verifyCode', 'validators.CaptchaValidator'];
        }

        return $rules;
    }

    public function init()
    {
        $this->gs_list = Gs::getOpenServers();

        if (count($this->gs_list) == 1) {
            $this->gs_id = key($this->gs_list);
        }
    }

    /**
     * Проверка сервера
     *
     * @param $attr
     * @param $params
     */
    public function gsIsExists($attr, $params)
    {
        if (!isset($this->gs_list[$this->gs_id])) {
            $this->addError(__FUNCTION__, 'Выберите сервер.');
        }
    }

    public function getLsId()
    {
        return $this->gs_list[$this->gs_id]['login_id'];
    }

    /**
     * Проверка Логина и Email
     *
     * @param $attr
     * @param $params
     */
    public function loginIsExists($attr, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if ($user === null) {
                $this->addError(__FUNCTION__, 'Аккаунт не найден.');
            } elseif ($user->isBanned()) {
                $this->addError(__FUNCTION__, 'Аккаунт заблокирован, восстановление пароля невозможно');
            } elseif (!$user->isActivated()) {
                $this->addError(__FUNCTION__, 'Аккаунт ещё не активирован');
            } else {
                // Ищю аккаунт на сервере
                try {
                    $l2 = l2('ls', $this->getLsId())->connect();

                    $res = $l2->getDb()->createCommand("SELECT * FROM {{accounts}} WHERE login = :login LIMIT 1")
                        ->bindParam('login', $this->login, PDO::PARAM_STR)
                        ->queryScalar();

                    if (!$res) {
                        $this->addError(__FUNCTION__, 'Аккаунт не найден.');
                    }
                } catch (Exception $e) {
                    $this->addError(__FUNCTION__, $e->getMessage());
                }
            }
        }
    }

    /**
     * @return Users|null
     */
    public function getUser()
    {
        if (!$this->user) {
            $this->user = Users::model()->find('login = :login AND email = :email AND ls_id = :ls_id', [
                ':login' => $this->login,
                ':email' => $this->email,
                'ls_id' => $this->getLsId(),
            ]);
        }
        return $this->user;
    }

    public function attributeLabels()
    {
        return [
            'gs_id' => 'Сервер',
            'login' => 'Логин',
            'email' => 'Email',
            'verifyCode' => 'Код с картинки',
        ];
    }
}
 