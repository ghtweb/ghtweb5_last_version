<?php

/**
 * Class RegisterForm
 *
 * @property Gs[] $gs_list
 * @property int $gs_id
 * @property Lineage $l2
 * @property string $prefix
 * @property string $login
 * @property string $password
 * @property string $re_password
 * @property string $email
 * @property string $referer
 * @property string $verifyCode
 * @property Users $refererInfo
 */
class RegisterForm extends CFormModel
{
    /**
     * @var Gs[]
     */
    public $gs_list = [];

    /**
     * @var int
     */
    public $gs_id;

    /**
     * @var Lineage
     */
    public $l2;

    /**
     * @var string
     */
    public $prefix;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $re_password;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $referer = '';

    /**
     * @var string
     */
    public $verifyCode;

    /**
     * @var Users
     */
    public $refererInfo;

    private $registrationInfoFileToken;

    public function init()
    {
        if (isset(request()->cookies[app()->params['cookie_referer_name']])) {
            $this->referer = request()->cookies[app()->params['cookie_referer_name']];
        }
    }

    public function rules()
    {
        $rules = [
            ['gs_id,login,password,re_password,email', 'filter', 'filter' => 'trim'],
            ['gs_id,login,password,re_password,email', 'required'],
            ['login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH],
            ['password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH],
            ['re_password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH],
            ['re_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Поля «{compareAttribute}» и «{attribute}» не совпадают.'],
            ['email', 'email', 'message' => 'Введите корректный Email адрес.'],
            ['email', 'checkBadEmail'],
            ['login', 'checkLoginChars'],
            ['gs_id', 'gsIsExists'],
            ['login', 'loginUnique'],
        ];

        // Captcha
        $captcha = config('register.captcha.allow') && CCaptcha::checkRequirements();

        if ($captcha) {
            $rules[] = ['verifyCode', 'filter', 'filter' => 'trim'];
            $rules[] = ['verifyCode', 'required'];
            $rules[] = ['verifyCode', 'validators.CaptchaValidator'];
        }

        // Prefix
        if (config('prefixes.allow') && config('prefixes.length') > 0 && config('prefixes.count_for_list') > 0) {
            $rules[] = ['prefix', 'filter', 'filter' => 'trim'];
            $rules[] = ['prefix', 'required'];
            $rules[] = ['prefix', 'checkPrefix'];
        }

        // Referral program
        if (config('referral_program.allow')) {
            $rules[] = ['referer', 'filter', 'filter' => 'trim'];
            $rules[] = ['referer', 'length', 'allowEmpty' => true, 'min' => Users::REFERER_MIN_LENGTH, 'max' => Users::REFERER_MAX_LENGTH];
            $rules[] = ['referer', 'refererIsExists'];
        }

        // Unique email
        if (!config('register.multiemail')) {
            $rules[] = ['email', 'emailUnique'];
        }

        return $rules;
    }

    /**
     * Проверка префикса
     *
     * @param string $attr
     */
    public function checkPrefix($attr)
    {
        if (!$this->hasErrors($attr)) {
            $prefix = $this->$attr;
            $prefixes = user()->getState('prefixes');

            if (is_array($prefixes) && !in_array($prefix, $prefixes)) {
                $this->addError($attr, 'Выберите префикс для логина.');
            }

            user()->setState('prefixes', null);
        }
    }

    /**
     * Проверка символов в логине
     *
     * @param string $attr
     */
    public function checkLoginChars($attr)
    {
        if (!$this->hasErrors($attr)) {
            $pattern = '/^[' . Users::LOGIN_REGEXP . ']{' . Users::LOGIN_MIN_LENGTH . ',' . Users::LOGIN_MAX_LENGTH . '}$/';

            if (!preg_match($pattern, $this->$attr)) {
                $this->addError($attr, 'В логине разрешены следующие символы: ' . Users::LOGIN_REGEXP);
            }
        }
    }

    protected function afterConstruct()
    {
        $this->gs_list = Gs::getOpenServers();

        if (count($this->gs_list) == 1) {
            $this->gs_id = key($this->gs_list);
        }

        parent::afterConstruct();
    }

    /**
     * Проверка реферера
     *
     * @param attributes
     * @param $params
     */
    public function refererIsExists($attributes, $params)
    {
        $cookieName = app()->params['cookie_referer_name'];
        $cookie = request()->cookies[$cookieName];

        if ($this->referer == '' && isset($cookie->value)) {
            $this->referer = $cookie->value;
        }

        if ($this->referer != '') {
            $lsId = $this->gs_list[$this->gs_id]['login_id'];

            $this->refererInfo = Users::model()->find('referer = :referer AND ls_id = :ls_id', [
                'referer' => $this->referer,
                'ls_id' => $lsId,
            ]);

            if (!$this->refererInfo) {
                $this->referer = '';
            }
        }
    }

    /**
     * Проверка сервера
     *
     * @param $attributes
     * @param $params
     */
    public function gsIsExists($attributes, $params)
    {
        if (!isset($this->gs_list[$this->gs_id])) {
            $this->addError(__FUNCTION__, 'Выберите сервер.');
        }
    }

    /**
     * Проверка Email адреса в черном списке
     *
     * @param $attribute
     * @param $params
     */
    public function checkBadEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (is_file($path = Yii::getPathOfAlias('app.data') . '/badEmails.txt') && is_readable($path)) {
                $emails = file_get_contents($path);
                $emails = explode("\n", $emails);
                $email = explode('@', $this->email);
                $email = $email[1];

                foreach ($emails as $v) {
                    $v = trim($v);

                    if ($v == $email) {
                        $this->addError(__FUNCTION__, 'Email ' . $this->email . ' в списке запрещенных, введите другой.');
                        break;
                    }
                }
            }
        }
    }

    /**
     * Проверка Email на уникальность
     *
     * @param $attribute
     * @param $params
     */
    public function emailUnique($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $email = $this->email;
            $lsId = $this->gs_list[$this->gs_id]['login_id'];

            $res = db()->createCommand("SELECT COUNT(0) FROM {{users}} WHERE email = :email AND ls_id = :ls_id LIMIT 1")
                ->bindParam('email', $email, PDO::PARAM_STR)
                ->bindParam('ls_id', $lsId, PDO::PARAM_INT)
                ->queryScalar();

            if ($res) {
                $this->addError('email', 'Email ' . $this->email . ' уже существует.');
            }
        }
    }

    /**
     * Проверка Логина на уникальность
     *
     * @param $attribute
     * @param $params
     */
    public function loginUnique($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $login = $this->getLogin();
            $lsId = $this->gs_list[$this->gs_id]['login_id'];

            $res = db()->createCommand("SELECT COUNT(0) FROM {{users}} WHERE login = :login AND ls_id = :ls_id LIMIT 1")
                ->bindParam('login', $login, PDO::PARAM_STR)
                ->bindParam('ls_id', $lsId, PDO::PARAM_INT)
                ->queryScalar();

            if ($res) {
                $this->addError('login', 'Логин ' . $login . ' уже существует.');
                return;
            }

            // Проверка логина на сервере
            try {
                $this->l2 = l2('ls', $lsId)->connect();

                $res = $this->l2->getDb()->createCommand("SELECT COUNT(0) FROM {{accounts}} WHERE login = :login LIMIT 1")
                    ->bindParam('login', $login, PDO::PARAM_STR)
                    ->queryScalar();

                if ($res) {
                    $this->addError('login', 'Логин ' . $login . ' уже существует.');
                }
            } catch (Exception $e) {
                $this->addError('login', $e->getMessage());
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'gs_id' => 'Сервер',
            'prefix' => 'Префикс',
            'login' => 'Логин',
            'password' => 'Пароль',
            're_password' => 'Повтор пароля',
            'email' => 'Email',
            'referer' => 'Реферальный код',
            'verifyCode' => 'Код с картинки',
        ];
    }

    public function getPrefixes()
    {
        $prefixes = [];
        $length = config('prefixes.length');

        for ($i = 0; $i < config('prefixes.count_for_list'); $i++) {
            $prefixes[] = strtolower(randomString($length));
        }

        user()->setState('prefixes', $prefixes);

        return array_combine($prefixes, $prefixes);
    }

    /**
     * Возвращает логин с префиксом
     *
     * @return string
     */
    public function getLogin()
    {
        return strtolower($this->prefix . $this->login);
    }

    /**
     * Регистрация аккаунта
     */
    public function registerAccount()
    {
        if (!$this->validate()) {
            return false;
        }

        $login = $this->getLogin();

        // Регистрация через почту
        if (config('register.confirm_email')) {
            $activatedHash = Users::generateActivatedHash();

            $user = $this->_createAccount();

            if (!$user) {
                $this->addError('login', 'Не удалось создать аккаунт');
                return false;
            }

            notify()->registerStep1($this->email, [
                'hash' => $activatedHash,
            ]);

            $cache = new CFileCache();
            $cache->init();

            $cache->set('registerActivated' . $activatedHash, [
                'user_id' => $user->getPrimaryKey(),
                'password' => $this->password,
                'email' => $this->email,
            ], (int)config('register.confirm_email.time') * 60);

            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'На почту <b>' . $this->email . '</b> отправлены инструкции по активации аккаута.');
        } else {
            $ls_transaction = $this->l2->getDb()->beginTransaction();

            try {
                $user = $this->_createAccount();

                if (!$user) {
                    $this->addError('login', 'Не удалось создать аккаунт');
                    return false;
                }

                // Создаю аккаунт на сервере
                $this->l2->insertAccount($login, $this->password);

                notify()->registerNoEmailActivated($this->email, [
                    'server_name' => $this->gs_list[$this->gs_id]['name'],
                    'login' => $login,
                    'password' => $this->password,
                    'referer' => $user->referer,
                ]);

                $ls_transaction->commit();

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Вы успешно зарегистрировали аккаунт. Приятной игры.');

                $this->saveDataFromRegisterInfoFile();
                $this->generateRegistrationInfoFileToken();
            } catch (Exception $e) {
                $ls_transaction->rollback();

                user()->setFlash(FlashConst::MESSAGE_ERROR, 'Произошла ошибка! Попробуйте повторить позже.');
                Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, __FILE__ . '::' . __LINE__);
            }
        }

        return true;
    }

    public static function getCacheNameForRegisterInfoFile()
    {
        return 'register-data' . userIp();
    }

    private function generateRegistrationInfoFileToken()
    {
        $this->registrationInfoFileToken = md5(userIp() . time() . rand(1, 9999) . $this->getLogin() . $this->password);
    }

    public function getRegistrationInfoFileToken()
    {
        return $this->registrationInfoFileToken;
    }

    /**
     * Загрузка файла с данными после регистрации
     *
     * @return void
     */
    public function saveDataFromRegisterInfoFile()
    {
        $cacheName = self::getCacheNameForRegisterInfoFile();
        cache()->set($cacheName, [
            $this->getLogin(),
            $this->login,
            $this->password,
            $this->email,
            $this->gs_id,
        ], 60);
    }

    /**
     * Создание аккаунта на сайте
     *
     * @return Users
     */
    private function _createAccount()
    {
        $transaction = db()->beginTransaction();

        $login = $this->getLogin();

        try {

            $user = Users::create(
                $login,
                $this->email,
                (config('register.confirm_email') ? Users::STATUS_INACTIVATED : Users::STATUS_ACTIVATED),
                $this->gs_list[$this->gs_id]['login_id'],
                Users::ROLE_DEFAULT
            );

            $user->save(false);

            // Referer
            if ($this->referer != '' && $this->refererInfo) {
                $referralsModel = Referals::create(
                    $this->refererInfo->getPrimaryKey(),
                    $user->getPrimaryKey()
                );
                $referralsModel->save(false);
            }

            // Удаляю реферальную куку
            if (isset(request()->cookies[app()->params['cookie_referer_name']])) {
                unset(request()->cookies[app()->params['cookie_referer_name']]);
            }

            $transaction->commit();

            return $user;

        } catch (Exception $e) {

            $transaction->rollback();

            // Удаляю созданный аккаунт на сервере
            $this->l2->getDb()->createCommand("DELETE FROM {{accounts}} WHERE login = :login LIMIT 1")
                ->bindParam('login', $login, PDO::PARAM_STR)

                ->execute();

            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, __FILE__ . '::' . __LINE__);

            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Произошла ошибка! Попробуйте повторить позже.');

        }

        return false;
    }
}
 