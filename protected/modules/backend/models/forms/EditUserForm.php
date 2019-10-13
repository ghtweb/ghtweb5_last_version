<?php

namespace app\modules\backend\models\forms;

\Yii::import('ext.MyValidators.ValidIp');

class EditUserForm extends \CFormModel
{
    public $role;
    public $activated;
    public $balance;
    public $phone;
    public $protected_ip;

    private $userModel;

    public function __construct(\Users $userModel, string $scenario = '')
    {
        parent::__construct($scenario);

        $this->role = $userModel->role;
        $this->activated = $userModel->activated;
        $this->balance = $userModel->profile->balance;
        $this->phone = $userModel->profile->phone;

        if ($userModel->profile->protected_ip && is_array($userModel->profile->protected_ip)) {
            $this->protected_ip = implode(PHP_EOL, $userModel->profile->protected_ip);
        }

        $this->userModel = $userModel;
    }

    public function rules()
    {
        return [
            ['role, activated, balance', 'required'],
            ['role', 'in', 'range' => array_keys(self::getRoleList()), 'message' => 'Выберите роль'],
            ['activated', 'in', 'range' => array_keys(self::getActivatedStatusList()), 'message' => 'Выберите статус аккаунта'],
            ['balance', 'numerical', 'message' => 'Введите число'],
            ['protected_ip', 'ValidIp'],
            ['phone', 'length', 'max' => 54],
        ];
    }

    public function attributeLabels()
    {
        return [
            'role' => 'Роль',
            'activated' => 'Статус аккаунта',
            'balance' => 'Баланс',
            'phone' => 'Телефон',
            'protected_ip' => 'Защита по IP',
        ];
    }

    public static function getRoleList()
    {
        return \Users::model()->getRoleList();
    }

    public static function getActivatedStatusList()
    {
        return \Users::model()->getActivatedStatusList();
    }

    public function editData()
    {
        $transaction = db()->beginTransaction();

        try {
            $this->userModel->role = $this->role;
            $this->userModel->activated = $this->activated;
            $this->userModel->profile->balance = $this->balance;
            $this->userModel->profile->phone = $this->phone;
            $this->userModel->profile->protected_ip = $this->protected_ip;

            $this->userModel->save(false);
            $this->userModel->profile->save(false);

            $transaction->commit();

            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
 