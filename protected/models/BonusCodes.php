<?php

/**
 * This is the model class for table "{{bonus_codes}}".
 *
 * The followings are the available columns in table '{{bonus_codes}}':
 * @property string $id
 * @property string $bonus_id
 * @property string $code
 * @property string $limit
 * @property integer $status
 * @property integer $count_activation
 * @property Bonuses $bonusInfo
 * @property BonusCodesActivatedLogs[] $bonusLog
 */
class BonusCodes extends ActiveRecord
{
    public function tableName()
    {
        return '{{bonus_codes}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => BonusCodes::class,
        ];

        return $behaviors;
    }

    public function relations()
    {
        return [
            'bonusInfo' => [self::HAS_ONE, 'Bonuses', ['id' => 'bonus_id']],
            'bonusLog' => [self::HAS_MANY, 'BonusCodesActivatedLogs', ['code_id' => 'id']],
        ];
    }

    /**
     * @param string $code
     *
     * @return \BonusCodes
     */
    public static function findByCode($code)
    {
        return self::model()->with(['bonusInfo' => [
            'joinType' => 'inner join',
            'scopes' => 'opened',
        ]])->opened()->find('code = :code', ['code' => $code]);
    }

    public function limitIsOver()
    {
        return $this->limit <= $this->count_activation;
    }
}
