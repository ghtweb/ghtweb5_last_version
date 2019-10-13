<?php

/**
 * This is the model class for table "{{bonus_codes_activated_logs}}".
 *
 * The followings are the available columns in table '{{bonus_codes_activated_logs}}':
 * @property string $id
 * @property string $code_id
 * @property string $user_id
 * @property string $created_at
 */
class BonusCodesActivatedLogs extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{bonus_codes_activated_logs}}';
    }
}
