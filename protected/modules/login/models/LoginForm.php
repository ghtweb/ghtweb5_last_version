<?php

/**
 * Модель формы авторизации
 *
 * Class LoginForm
 */
class LoginForm extends CFormModel
{
    /**
     * Логин
     * @var string
     */
    public $login;

    /**
     * Пароль
     * @var string
     */
    public $password;

    /**
     * Список серверов
     * @var Gs[]
     */
    public $gs_list;

    /**
     * Выбранный сервер
     * @var int
     */
    public $gs_id;

    /**
     * ID логина от выбранного сервера
     * @var
     */
    public $ls_id;

    /**
     * Код с картинки
     * @var string
     */
    public $verifyCode;


    public function rules()
    {
        $rules = [
            ['gs_id,login,password', 'filter', 'filter' => 'trim'],
            ['gs_id,login,password', 'required'],
            ['login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH],
            ['password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH],
            ['login', 'loginExists'],
            ['gs_id', 'gsIsExists'],
        ];

        // Captcha
        $captcha = config('login.captcha.allow') && CCaptcha::checkRequirements();

        if ($captcha) {
            $rules[] = ['verifyCode', 'filter', 'filter' => 'trim'];
            $rules[] = ['verifyCode', 'required'];
            $rules[] = ['verifyCode', 'validators.CaptchaValidator'];
        }

        return $rules;
    }

    protected function afterConstruct()
    {
        $this->gs_list = Gs::getOpenServers();

        if (count($this->gs_list) == 1) {
            $this->gs_id = key($this->gs_list);
        }

        parent::afterConstruct();
    }

    protected function afterValidate()
    {
        $this->ls_id = $this->gs_list[$this->gs_id]['login_id'];

        parent::afterValidate();
    }

    /**
     * Проверка логина на сервере
     *
     * @param $attr
     */
    public function loginExists($attr)
    {
        if (!$this->hasErrors($attr)) {

            try {

                $siteAccountUserId = null;
                $found = false;
                $login = $this->getLogin();
                $lsId = $this->getLsId();

                $l2 = l2('ls', $lsId)->connect();

                $account = $l2->getDb()
                    ->createCommand()
                    ->where('login = :login', ['login' => $login])
                    ->from('accounts')
                    ->queryRow();

                /** @var Users $siteAccount */
                $siteAccount = Users::model()->find('login = :login AND ls_id = :ls_id', ['login' => $login, 'ls_id' => $lsId]);

                if ($siteAccount) {
                    $siteAccountUserId = $siteAccount->user_id;
                }

                // Аккаунт на сервере найден
                if ($account) {
                    if ($account['password'] == $l2->passwordEncrypt($this->getPassword())) {
                        // Аккаунта на сайте нет, создаю его так как на сервере он уже есть
                        if (!$siteAccount) {
                            $email = null;

                            $columnNames = $l2->getDb()
                                ->getSchema()
                                ->getTable('accounts')
                                ->getColumnNames();

                            if (is_array($columnNames)) {
                                foreach ($columnNames as $column) {
                                    if (strpos($column, 'mail') !== false && isset($account[$column])) {
                                        $email = $account[$column];
                                    }
                                }
                            }

                            // В таблице с аккаунтами нет поля с Email. Нет смысла регать дальше аккаунт на сайте
                            if (!$email) {
                                throw new Exception('Аккаунт не найден');
                            }

                            // Создаю аккаунт на сайте
                            $userModel = Users::create($login, $email, Users::STATUS_ACTIVATED, $lsId, Users::ROLE_DEFAULT);
                            $userModel->save(false);

                            $siteAccountUserId = $userModel->user_id;
                        }

                        $found = true;
                    }
                }

                // Аккаунт не найден
                if (!$found) {
                    if ($siteAccountUserId) {
                        UsersAuthLogs::model()->addErrorAuth($siteAccountUserId);
                    }

                    $this->incrementBadAttempt();
                    $this->addError($attr, 'Неправильный Логин или Пароль.');
                }
            } catch (Exception $e) {
                $this->addError('login', $e->getMessage());
            }
        }
    }

    /**
     * Проверка сервера
     *
     * @param string $attribute
     */
    public function gsIsExists($attribute)
    {
        if (!isset($this->gs_list[$this->gs_id])) {
            $this->addError($attribute, 'Выберите сервер.');
        }
    }

    public function attributeLabels()
    {
        return [
            'gs_id' => 'Сервер',
            'login' => 'Логин',
            'password' => 'Пароль',
            'verifyCode' => 'Код с картинки',
        ];
    }

    public function login()
    {
        $identity = new UserIdentity($this->login, $this->ls_id, $this->gs_id);
        $identity->authenticate();

        switch ($identity->errorCode) {
            case UserIdentity::ERROR_USERNAME_INVALID:
                $this->addError('status', 'Неправильный Логин или Пароль.');
                break;
            case UserIdentity::ERROR_STATUS_INACTIVE:
                $this->addError('status', 'Аккаунт не активирован.');
                break;
            case UserIdentity::ERROR_STATUS_BANNED:
                $this->addError('status', 'Аккаунт заблокирован.');
                break;
            case UserIdentity::ERROR_STATUS_IP_NO_ACCESS:
                $this->addError('status', 'С Вашего IP нельзя зайти на аккаунт.');
                break;
            case UserIdentity::ERROR_NONE:
                $identity->setState('gs_id', $this->gs_id);

                $this->clearBadAttempt();

                $duration = 3600 * 24 * 7; // 7 days
                user()->login($identity, $duration);

                return true;
        }

        return false;
    }

    /**
     * Логин
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * ID логин сервера
     *
     * @return int
     */
    public function getLsId()
    {
        return $this->gs_list[$this->gs_id]['login_id'];
    }

    /**
     * @return Gs[]
     */
    public function getGsList()
    {
        return $this->gs_list;
    }

    /**
     * @return int
     */
    public function getGsId()
    {
        return $this->gs_id;
    }

    /**
     * @return CFileCache
     */
    private function getCache()
    {
        static $cache;

        if (!$cache) {
            $cache = new CFileCache();
            $cache->init();
        }

        return $cache;
    }

    /**
     * @return string
     */
    private function getCacheName()
    {
        return 'count.failed.attempts' . userIp();
    }

    /**
     * Добавление неудачной попытки входа
     *
     * @return void
     */
    public function incrementBadAttempt()
    {
        $cacheName = $this->getCacheName();
        $cache = $this->getCache();
        $count = $this->getCountBadAttempt();

        $cache->set($cacheName, ++$count, (int)config('login.failed_attempts_blocked_time') * 60);
    }

    /**
     * @return void
     */
    public function clearBadAttempt()
    {
        $cacheName = $this->getCacheName();
        $cache = $this->getCache();

        $cache->delete($cacheName);
    }

    /**
     * @return int
     */
    public function getCountBadAttempt()
    {
        $cacheName = $this->getCacheName();
        $cache = $this->getCache();

        $count = $cache->get($cacheName);

        return $count === false
            ? 0
            : $count;
    }

    /**
     * @return bool
     */
    public function isBlockedForm()
    {
        return (int)config('login.failed_attempts_blocked_time') > 0 && $this->getCountBadAttempt() >= (int)config('login.count_failed_attempts_for_blocked');
    }
}
