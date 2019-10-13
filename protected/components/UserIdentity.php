<?php

class UserIdentity extends CUserIdentity
{
    private $_id;

    const ERROR_STATUS_INACTIVE = 3;
    const ERROR_STATUS_BANNED = 4;
    const ERROR_STATUS_IP_NO_ACCESS = 5;


    /**
     * @var Users
     */
    private $_user;
    private $_ls_id;
    private $_gs_id;


    public function __construct($username, $ls_id, $gs_id)
    {
        $this->username = $username;
        $this->_ls_id = $ls_id;
        $this->_gs_id = $gs_id;
    }

    public function authenticate()
    {
        $userIp = userIp();
        $this->_user = Users::model()->with('profile')->find('login = :login AND ls_id = :ls_id', [
            'login' => $this->username,
            'ls_id' => $this->_ls_id,
        ]);

        if ($this->_user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!$this->_user->isActivated()) {
            $this->errorCode = self::ERROR_STATUS_INACTIVE;
        } elseif ($this->_user->isBanned()) {
            $this->errorCode = self::ERROR_STATUS_BANNED;
        } elseif ($this->_user->profile->protected_ip && is_array($this->_user->profile->protected_ip) && !in_array($userIp, $this->_user->profile->protected_ip)) {
            $this->errorCode = self::ERROR_STATUS_IP_NO_ACCESS;
        } else {
            $this->_id = $this->_user->getPrimaryKey();

            $this->_user->auth_hash = Users::generateAuthHash();

            $this->setState('auth_hash', $this->_user->auth_hash);
            $this->setState('gs_id', $this->_gs_id);
            $this->setState('ls_id', $this->_user->ls_id);

            UsersAuthLogs::model()->addSuccessAuth($this->_id);

            $this->_user->save(false, ['auth_hash', 'updated_at']);

            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function getName()
    {
        return $this->username;
    }

    public function getId()
    {
        return $this->_id;
    }
}
