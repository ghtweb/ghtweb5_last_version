<?php

class WebUser extends CWebUser
{
    /**
     * @var Users
     */
    protected $_user;


    protected function beforeLogout()
    {
        $userId = $this->getId();
        $lsId = $this->getLsId();
        $date = date('Y-m-d H:i:s');

        db()->createCommand("UPDATE {{users}} SET auth_hash = NULL, updated_at = :updated_at WHERE user_id = :user_id AND ls_id = :ls_id LIMIT 1")
            ->bindParam('user_id', $userId, PDO::PARAM_INT)
            ->bindParam('ls_id', $lsId, PDO::PARAM_INT)
            ->bindParam('updated_at', $date, PDO::PARAM_STR)
            ->execute();

        return parent::beforeLogout();
    }


    public function init()
    {
        parent::init();

        if ($this->getIsGuest() === false) {
            // Если юзер залогинен то обновляю его инфу
            $this->_user = Users::model()->with('profile')->find('auth_hash = :auth_hash', [
                'auth_hash' => $this->getState('auth_hash'),
            ]);

            if (!$this->_user) {
                $this->logout();
            }
        }
    }

    /**
     * Возвращает список персонажей
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getCharacters()
    {
        $cacheName = strtr(CacheNames::CHARACTER_LIST, [
            ':gs_id' => $this->getGsId(),
            ':user_id' => $this->getId(),
        ]);

        if (($characters = cache()->get($cacheName)) === false) {
            try {
                $l2 = l2('gs', $this->getGsId())->connect();

                $command = $l2->getDb()->createCommand();
                $command->where('account_name = :account_name', [':account_name' => $this->get('login')]);
                $command->setOrder('char_name');

                $characters = $l2->characters($command)->queryAll();

                cache()->set($cacheName, $characters, 300);
            } catch (Exception $e) {
                throw new Exception('Не удалось выбрать всех персонажей с сервера.');
            }
        }

        return $characters;
    }

    public function get($key, $default = null)
    {
        if (isset($this->_user->$key)) {
            return $this->_user->$key;
        }

        if (isset($this->_user->profile->$key)) {
            return $this->_user->profile->$key;
        }

        return $default;
    }

    public function getCreated_at()
    {
        if ($this->_user) {
            return $this->_user->getCreatedAt();
        }

        return '';
    }

    public function getGsId()
    {
        return (int)$this->getState('gs_id');
    }

    public function getLsId()
    {
        return (int)$this->getState('ls_id');
    }

    public function getLogin()
    {
        return ($this->_user !== null ? $this->_user->login : '');
    }

    // Deprecated
    public function getBalance()
    {
        return $this->get('balance');
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->_user;
    }
}
 