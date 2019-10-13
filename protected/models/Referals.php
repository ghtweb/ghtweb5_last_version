<?php

/**
 * This is the model class for table "{{referals}}".
 *
 * The followings are the available columns in table '{{referals}}':
 * @property string $referer
 * @property string $referal
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Users $referalInfo
 */
class Referals extends ActiveRecord
{
    public function tableName()
    {
        return '{{referals}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => self::class,
            'idAttributeName' => 'referer',
        ];

        return $behaviors;
    }

    public function relations()
    {
        return [
            'referalInfo' => [self::HAS_ONE, 'Users', ['user_id' => 'referal']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'referer' => 'ID кто пригласил',
            'referal' => 'ID кого пригласили',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * @param int $userId
     * @return self[]
     */
    public static function getByUserId($userId)
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class . 'referer:' . $userId),
        ]);

        $list = [];

        /** @var self[] $res */
        $res = self::model()->cache(3600, $dependency)->findAll('referer = ?', [$userId]);

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }

    /**
     * @param int $referer  ID юзера кто зарегался
     * @param int $referral   ID юзера по чьей ссылке зарегались
     * @return Referals
     */
    public static function create(int $referer, int $referral): self
    {
        $model = new self();

        $model->referer = $referer;
        $model->referal = $referral;

        return $model;
    }
}
