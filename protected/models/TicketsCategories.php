<?php

/**
 * This is the model class for table "{{tickets_categories}}".
 *
 * The followings are the available columns in table '{{tickets_categories}}':
 * @property string $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 */
class TicketsCategories extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{tickets_categories}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => TicketsCategories::class,
        ];

        return $behaviors;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'tickets' => [self::BELONGS_TO, 'Tickets', 'category_id'],
        ];
    }

    /**
     * Возвращает список открытых категорий
     *
     * @return TicketsCategories[]
     */
    public static function getOpenCategories()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class)
        ]);
        $list = [];

        /** @var TicketsCategories[] $res */
        $res = TicketsCategories::model()->cache(3600, $dependency)->opened()->findAll(['order' => 'sort']);

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }
}
