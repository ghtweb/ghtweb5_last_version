<?php

/**
 * This is the model class for table "{{bonuses}}".
 *
 * The followings are the available columns in table '{{bonuses}}':
 * @property string $id
 * @property string $title
 * @property string $date_end
 * @property integer $status
 *
 * @property BonusesItems[] $items
 * @property int $itemCount
 */
class Bonuses extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{bonuses}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Bonuses::class,
        ];

        return $behaviors;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'items' => [self::HAS_MANY, 'BonusesItems', ['bonus_id' => 'id']],
            'itemCount' => [self::STAT, 'BonusesItems', 'bonus_id'],
        ];
    }

    public function afterDelete()
    {
        BonusesItems::model()->deleteAll('bonus_id = :bonus_id', [':bonus_id' => $this->id]);
        UserBonuses::model()->deleteAll('bonus_id = :bonus_id', [':bonus_id' => $this->id]);
    }

    /**
     * Возвращает дату окончания бонуса
     *
     * @return string
     */
    public function getDateEnd()
    {
        if ($this->date_end) {
            return date('Y-m-d H:i', strtotime($this->date_end));
        }

        return '--';
    }

    /**
     * @return Bonuses[]
     */
    public static function getOpenBonuses()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(Bonuses::class),
        ]);
        $list = [];

        /** @var Bonuses[] $res */
        $res = Bonuses::model()->cache(3600, $dependency)->opened()->findAll();

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }

    public function isTimeEnded()
    {
        if ($this->date_end) {
            return $this->date_end < date('Y-m-d H:i:s');
        }
        return false;
    }
}
