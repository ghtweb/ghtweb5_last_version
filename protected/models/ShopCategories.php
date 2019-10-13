<?php

/**
 * This is the model class for table "{{shop_categories}}".
 *
 * The followings are the available columns in table '{{shop_categories}}':
 * @property string $id
 * @property string $name
 * @property string $link
 * @property integer $sort
 * @property integer $status
 * @property integer $gs_id
 *
 * @property ShopItemsPacks[] $packs
 * @property ShopItemsPacks $pack
 * @property int $countPacks
 */
class ShopCategories extends ActiveRecord
{
    public function tableName()
    {
        return '{{shop_categories}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => ShopCategories::class,
            'idAttributeName' => 'gs_id',
        ];

        return $behaviors;
    }

    /**
     * @param int $gsId
     * @return ShopCategories[]
     */
    public static function getOpenCategories($gsId)
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class . 'gs_id:' . $gsId),
        ]);

        $list = [];

        /** @var ShopCategories[] $res */
        $res = self::model()->cache(3600, $dependency)->opened()->findAll('gs_id = :gs_id', ['gs_id' => $gsId]);

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $criteria = new CDbCriteria([
            'condition' => 'category_id = :category_id',
            'params' => [
                ':category_id' => $this->getPrimaryKey()
            ],
            'with' => ['items'],
        ]);

        $model = ShopItemsPacks::model()->findAll($criteria);

        foreach ($model as $pack) {
            foreach ($pack->items as $item) {
                $item->delete();
            }
            $pack->delete();
        }
    }

    public function relations()
    {
        return [
            'packs' => [self::HAS_MANY, 'ShopItemsPacks', 'category_id',
                //'order'     => 'packs.sort',
                //'condition' => 'packs.status = 1',
                //'joinType' => 'LEFT JOIN',
            ],
            'pack' => [self::HAS_ONE, 'ShopItemsPacks', 'category_id'],
            'countPacks' => [self::STAT, 'ShopItemsPacks', 'category_id'],
        ];
    }
}
