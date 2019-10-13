<?php

use app\components\behaviors\image\ImgData;

/**
 * This is the model class for table "{{shop_items_packs}}".
 *
 * The followings are the available columns in table '{{shop_items_packs}}':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property integer $category_id
 * @property string $img
 * @property integer $sort
 * @property integer $status
 *
 * @property ShopItems[] $items
 * @property int $countItems
 */
class ShopItemsPacks extends ActiveRecord
{
    const IMAGES_PATH = 'images/shop/packs';

    public function tableName()
    {
        return '{{shop_items_packs}}';
    }

    public function relations()
    {
        return [
            'items' => [self::HAS_MANY, 'ShopItems', 'pack_id'],
            'countItems' => [self::STAT, 'ShopItems', 'pack_id'],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ImageBehavior'] = [
            'class' => 'application.components.behaviors.image.ImageBehavior',
            'uploadPath' => app()->params['uploadPath'] . '/' . self::IMAGES_PATH,
            'imgData' => [new ImgData('original', 150, 150)],
        ];

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => ShopItemsPacks::class,
        ];

        return $behaviors;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        // При удалении удаляю предметы из набора
        $items = ShopItems::model()->findAll('pack_id = :pack_id', [':pack_id' => $this->id]);

        foreach ($items as $item) {
            $item->delete();
        }
    }
}
