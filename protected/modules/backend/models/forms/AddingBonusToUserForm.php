<?php

namespace app\modules\backend\models\forms;

class AddingBonusToUserForm extends \UserBonuses
{
    /**
     * @var \Users
     */
    private $user;

    /**
     * @var \UserBonuses
     */
    private $userBonuses;

    public function __construct($userId, string $scenario = 'insert')
    {
        parent::__construct($scenario);

        $user = \Users::model()->findByPk($userId);

        if (is_null($user)) {
            throw new \CHttpException(404, 'Пользователь не найден');
        }

        $this->user = $user;
    }

    public function rules()
    {
        $rules = [];

        // user_id
        $rules[] = ['user_id', 'filter', 'filter' => 'trim'];

        // bonus_id
        $rules[] = ['bonus_id', 'numerical', 'integerOnly' => true];
        $rules[] = ['bonus_id', 'in', 'range' => array_keys($this->getBonusesList())];
        $rules[] = ['bonus_id', 'bonusIsExists'];

        return $rules;
    }

    public function bonusIsExists()
    {
        if (!$this->hasErrors()) {
            $bonusModel = $this->getUserBonuses();
            if ($bonusModel) {
                $this->addError('bonus_id', 'Бонус "' . $bonusModel->bonusInfo->title . '" уже есть у юзера');
            }
        }
    }

    /**
     * @return \UserBonuses
     */
    public function getUserBonuses()
    {
        if (is_null($this->userBonuses)) {
            $this->userBonuses = \UserBonuses::model()->find('bonus_id = :bonus_id and user_id = :user_id', [
                'bonus_id' => $this->bonus_id,
                'user_id' => $this->user->user_id,
            ]);
        }

        return $this->userBonuses;
    }

    /**
     * @return array
     */
    public function getBonusesList()
    {
        return \CHtml::listData(\Bonuses::getOpenBonuses(), 'id', 'title');
    }

    protected function beforeValidate()
    {
        $this->user_id = $this->user->user_id;

        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'bonus_id' => 'Бонус',
        ];
    }
}
