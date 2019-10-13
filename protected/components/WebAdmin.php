<?php

class WebAdmin extends CWebUser
{
    /**
     * @var Users
     */
    protected $_user;

    public function init()
    {
        parent::init();

        if ($this->getIsGuest() === false) {
            $this->_user = Admin::model()->find('auth_hash = :auth_hash', ['auth_hash' => $this->getState('auth_hash')]);
            if (!$this->_user) {
                $this->logout();
            }
        }
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->_user;
    }
}
 