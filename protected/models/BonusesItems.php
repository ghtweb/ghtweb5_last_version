<?php

/**
 * This is the model class for table "{{bonuses_items}}".
 *
 * The followings are the available columns in table '{{bonuses_items}}':
 * @property string $id
 * @property string $item_id
 * @property string $count
 * @property string $enchant
 * @property string $bonus_id
 * @property integer $status
 *
 * @property Bonuses $bonus
 * @property AllItems $itemInfo
 */
class BonusesItems extends ActiveRecord
{
    public function tableName()
    {
        return '{{bonuses_items}}';
    }

    public function relations()
    {
        return [
            'bonus' => [self::HAS_ONE, 'Bonuses', 'bonus_id'],
            'itemInfo' => [self::HAS_ONE, 'AllItems', ['item_id' => 'item_id']],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => BonusesItems::class,
        ];

        return $behaviors;
    }
}
