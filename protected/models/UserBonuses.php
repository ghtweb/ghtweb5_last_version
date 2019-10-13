<?php

/**
 * This is the model class for table "{{user_bonuses}}".
 *
 * The followings are the available columns in table '{{user_bonuses}}':
 * @property string $id
 * @property string $bonus_id
 * @property string $user_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $char_name
 *
 * @property Bonuses $bonusesModel
 * @property Bonuses $bonusInfo
 */
class UserBonuses extends ActiveRecord
{
    const STATE_ACTIVE = 1;
    const STATE_NOT_ACTIVE = 0;

    public function tableName()
    {
        return '{{user_bonuses}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => UserBonuses::class,
            'idAttributeName' => 'user_id',
        ];

        return $behaviors;
    }

    public function relations()
    {
        return [
            'bonusInfo' => [self::HAS_ONE, 'Bonuses', ['id' => 'bonus_id']],
        ];
    }

    public function getStateList()
    {
        return [
            self::STATE_ACTIVE => 'Активирован',
            self::STATE_NOT_ACTIVE => 'Не активирован',
        ];
    }

    public function getState()
    {
        return isset($this->getStateList()[$this->status])
            ? $this->getStateList()[$this->status]
            : '--';
    }

    public function isActivated()
    {
        return $this->status == self::STATE_ACTIVE;
    }
}
