<?php

/**
 * This is the model class for table "{{shop_items}}".
 *
 * The followings are the available columns in table '{{shop_items}}':
 * @property string $id
 * @property integer $pack_id
 * @property integer $item_id
 * @property string $description
 * @property double $cost
 * @property float $discount
 * @property integer $count
 * @property integer $enchant
 * @property integer $status
 * @property integer $sort
 *
 * @property ShopItemsPacks $pack
 * @property AllItems $itemInfo
 */
class ShopItems extends ActiveRecord
{
    public function tableName()
    {
        return '{{shop_items}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => ShopItems::class,
            'idAttributeName' => ['id', 'pack_id'],
        ];

        return $behaviors;
    }

    public function relations()
    {
        return [
//            'category' => [self::HAS_ONE, 'ShopCategory', 'category_id',
//                'order' => 'sort',
//            ],
            'itemInfo' => [self::HAS_ONE, 'AllItems', ['item_id' => 'item_id']],
            'pack' => [self::HAS_ONE, 'ShopItemsPacks', ['id' => 'pack_id']],
        ];
    }

    public static function costAtDiscount($cost, $discount)
    {
        if ($discount == 0) {
            return $cost;
        }

        return $cost - ($cost / 100) * $discount;
    }
}
