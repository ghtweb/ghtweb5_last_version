<?php

use app\services\PasswordManagerInterface;

class AdminIdentity extends CUserIdentity
{
    private $_id;

    const ERROR_STATUS_INACTIVE = 3;
    const ERROR_STATUS_BANNED = 4;
    const ERROR_STATUS_IP_NO_ACCESS = 5;

    /**
     * @var Admin
     */
    private $_user;
    private $passwordManager;


    public function __construct($username, $password, PasswordManagerInterface $passwordManager)
    {
        parent::__construct($username, $password);
        $this->passwordManager = $passwordManager;
    }

    public function authenticate()
    {
        $this->_user = Admin::model()->find('login = :login', ['login' => $this->username]);

        if ($this->_user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!$this->passwordManager->isValidated($this->password, $this->_user->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $this->_user->id;
            $this->_user->generateAuthHash();
            $this->_user->save(false, ['auth_hash', 'updated_at']);

            $this->setState('auth_hash', $this->_user->auth_hash);

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
