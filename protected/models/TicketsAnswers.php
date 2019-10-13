<?php

/**
 * This is the model class for table "{{tickets_answers}}".
 *
 * The followings are the available columns in table '{{tickets_answers}}':
 * @property string $id
 * @property string $ticket_id
 * @property string $text
 * @property string $user_id
 * @property string $created_at
 */
class TicketsAnswers extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{tickets_answers}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['text', 'filter', 'filter' => 'trim'],
            ['text', 'filter', 'filter' => 'strip_tags'],
            ['text', 'required'],
            ['text', 'length', 'min' => 5],

            ['id, ticket_id, text, user_id, created_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'userInfo' => [self::HAS_ONE, 'Users', ['user_id' => 'user_id']],
        ];
    }

    public function getDate()
    {
        return date('Y-m-d H:i', strtotime($this->created_at));
    }
}
