<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property string $user_id
 * @property string $login
 * @property string $email
 * @property int $activated
 * @property string $activated_hash
 * @property string $referer
 * @property string $role
 * @property string $auth_hash
 * @property string $registration_ip
 * @property integer $ls_id
 * @property string $updated_at
 * @property string $created_at
 * @property string $reset_password_hash
 *
 * The followings are the available model relations:
 * @property UserProfiles $profile
 * @property Transactions $transactions
 * @property Referals $referals
 * @property UserBonuses $bonuses
 * @property Ls $ls
 */
class Users extends ActiveRecord
{
    //const COUNT_FAILED_LOGIN_ATTEMPTS = 3; // Кол-во попыток перед показом капчи
    //const COUNT_FAILED_LOGIN_ATTEMPTS_FOR_BLOCKED_FORM = 10; // Кол-во попыток перед блокировкой формы


    // Login
    const LOGIN_MIN_LENGTH = 6;
    const LOGIN_MAX_LENGTH = 14;
    const LOGIN_REGEXP = 'A-Za-z0-9-';

    // Password
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 16;

    // Referer
    const REFERER_MIN_LENGTH = 6;
    const REFERER_MAX_LENGTH = 10;


    const ROLE_DEFAULT = 'user';
    const ROLE_BANNED = 'banned';
    /** @deprecated */
    const ROLE_ADMIN = 'admin';

    // Active status
    const STATUS_INACTIVATED = 0;
    const STATUS_ACTIVATED = 1;


    public function primaryKey()
    {
        return 'user_id';
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{users}}';
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'profile' => [self::HAS_ONE, 'UserProfiles', 'user_id'],
            'transactions' => [self::HAS_MANY, 'Transactions', 'user_id', 'order' => 'created_at DESC'],
            'referals' => [self::HAS_MANY, 'Referals', ['referer' => 'user_id']],
            'bonuses' => [self::HAS_MANY, 'UserBonuses', 'user_id'],
            'ls' => [self::HAS_ONE, 'Ls', ['id' => 'ls_id']],
        ];
    }

    public function scopes()
    {
        return [
            'activated' => [
                'condition' => 'activated = :activated',
                'params' => [':activated' => self::STATUS_ACTIVATED],
            ],
        ];
    }

    public function generateResetPasswordHash()
    {
        $this->reset_password_hash = self::generateActivatedHash() . '_' . time();
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->referer = self::generateRefererCode();
            $this->registration_ip = userIp();
        }

        return parent::beforeSave();
    }

    protected function afterSave()
    {
        if ($this->isNewRecord) {
            $model = new UserProfiles();
            $model->balance = UserProfiles::DEFAULT_BALANCE;
            $model->user_id = $this->getPrimaryKey();

            $model->save(false);
        }

        parent::afterSave();
    }

    /**
     * Генерация реферального кода
     *
     * @return string
     */
    public static function generateRefererCode()
    {
        while (true) {
            $code = strtolower(randomString(rand(self::REFERER_MIN_LENGTH, self::REFERER_MAX_LENGTH)));

            $res = db()->createCommand("SELECT COUNT(0) FROM {{users}} WHERE referer = :referer LIMIT 1")
                ->queryScalar([
                    'referer' => $code,
                ]);

            if (!$res) {
                break;
            }
        }

        return $code;
    }

    /**
     * Генерация кода для активации Мастер аккаунта
     *
     * @return string
     */
    public static function generateActivatedHash()
    {
        return md5(uniqid() . time() . userIp());
    }

    /**
     * Генерация уникального хэша для авторизации
     *
     * @return string
     */
    public static function generateAuthHash()
    {
        return self::generateActivatedHash();
    }

    public function removeResetPasswordHash()
    {
        $this->reset_password_hash = null;
    }

    /**
     * Генерация логина
     *
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string
     */
    public static function generateLogin($minLength = 6, $maxLength = 8)
    {
        return randomString(rand($minLength, $maxLength));
    }

    public function getActivatedStatusList()
    {
        return [
            self::STATUS_ACTIVATED => 'Активирован',
            self::STATUS_INACTIVATED => 'Не активирован',
        ];
    }

    public function getActivatedStatus()
    {
        return isset($this->getActivatedStatusList()[$this->activated])
            ? $this->getActivatedStatusList()[$this->activated]
            : '--';
    }

    /**
     * Список ролей
     *
     * @return array
     */
    public function getRoleList()
    {
        return [
            self::ROLE_DEFAULT => 'Юзер',
            self::ROLE_BANNED => 'Забанен',
        ];
    }

    /**
     * Текущая роль
     *
     * @return string
     */
    public function getRole()
    {
        return isset($this->getRoleList()[$this->role])
            ? $this->getRoleList()[$this->role]
            : '--';
    }

    /**
     * @return bool
     */
    public function isBanned()
    {
        return $this->role == self::ROLE_BANNED;
    }

    /**
     * @return bool
     */
    public function isActivated()
    {
        return $this->activated == self::STATUS_ACTIVATED;
    }

    public function setActivated()
    {
        $this->activated = self::STATUS_ACTIVATED;
    }

    public static function create(string $login, string $email, int $activatedStatus, int $lsId, string $role = Users::ROLE_DEFAULT): self
    {
        $model = new self();

        $model->login = $login;
        $model->email = $email;
        $model->activated = $activatedStatus;
        $model->role = $role;
        $model->ls_id = $lsId;

        return $model;
    }

    public static function isResetPasswordHashValid($hash): bool
    {
        if (empty($hash)) {
            return false;
        }
        $timestamp = (int) substr($hash, strrpos($hash, '_') + 1);
        $expire = (int) config('forgotten_password.cache_time') * 60;
        return $timestamp + $expire >= time();
    }
}
