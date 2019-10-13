<?php

/**
 * This is the model class for table "{{pages}}".
 *
 * The followings are the available columns in table '{{pages}}':
 * @property integer $id
 * @property string $page
 * @property string $title
 * @property string $text
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Pages extends ActiveRecord
{
    const PAGE_PATTERN = 'a-zA-Z0-9-_';


    public function tableName()
    {
        return '{{pages}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Pages::class,
            'idAttributeName' => 'page',
        ];

        return $behaviors;
    }

    /**
     * @return Pages[]
     */
    public static function getOpenList()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class),
        ]);

        $list = [];

        /** @var Pages[] $res */
        $res = Pages::model()->cache(3600, $dependency)->opened()->findAll();

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }
}
