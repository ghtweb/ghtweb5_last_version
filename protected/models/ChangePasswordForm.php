<?php

class ChangePasswordForm extends CFormModel
{
    /**
     * Старый пароль
     * @var string
     */
    public $old_password;

    /**
     * Новый пароль
     * @var string
     */
    public $new_password;

    /**
     * @var Lineage
     */
    private $_l2;


    public function rules()
    {
        return [
            ['old_password,new_password', 'filter', 'filter' => 'trim'],
            ['old_password,new_password', 'required'],
            ['old_password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH],
            ['new_password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH],
            ['old_password', 'checkOldPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'old_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
        ];
    }

    /**
     * Проверка старого пароля
     *
     * @param string $attr
     */
    public function checkOldPassword($attr)
    {
        if (!$this->hasErrors()) {
            try {
                $l2 = $this->getL2();
                $login = user()->get('login');
                $account = $l2->getDb()->createCommand("SELECT password FROM {{accounts}} WHERE login = :login LIMIT 1")
                    ->queryRow(true, [
                        'login' => $login,
                    ]);

                if (!isset($account['password'])) {
                    $this->addError($attr, 'Аккаунт не найден.');
                } elseif ($account['password'] != $l2->passwordEncrypt($this->old_password)) {
                    $this->addError($attr, 'Старый пароль и текущий пароли не совпадают.');
                }
            } catch (Exception $e) {
                $this->addError($attr, 'Произошла ошибка! Попробуйте повторить позже.');
            }
        }
    }

    /**
     * @return Lineage
     */
    public function getL2()
    {
        if (!$this->_l2) {
            $this->_l2 = l2('ls', user()->getLsId())->connect();
        }

        return $this->_l2;
    }

    public function changePassword()
    {
        try {
            $l2 = $this->getL2();

            $newPassword = $l2->passwordEncrypt($this->new_password);
            $login = user()->get('login');

            $res = $l2->getDb()->createCommand("UPDATE {{accounts}} SET password = :password WHERE login = :login LIMIT 1")
                ->bindParam('password', $newPassword, PDO::PARAM_STR)
                ->bindParam('login', $login, PDO::PARAM_STR)
                ->execute();

            if ($res) {
                if (user()->get('email')) {
                    notify()->changePassword(user()->get('email'), [
                        'password' => $this->new_password,
                    ]);
                }

                // Логирую действие юзера
                if (app()->params['user_actions_log']) {
                    $log = new UserActionsLog();

                    $log->user_id = user()->getId();
                    $log->action_id = UserActionsLog::ACTION_CHANGE_PASSWORD;

                    $log->save(false);
                }

                return true;
            }
        } catch (Exception $e) {
            Yii::log("Не удалось сменить пароль от аккаунта\nOld password: " . $this->old_password . "\nNew password: " . $this->new_password . "\nError: " . $e->getMessage() . "\n", CLogger::LEVEL_ERROR, 'cabinet_change_password');
        }

        return false;
    }
}
 