<?php

/**
 * This is the model class for table "{{purchase_items_log}}".
 *
 * The followings are the available columns in table '{{purchase_items_log}}':
 * @property integer $id
 * @property integer $pack_id
 * @property integer $item_id
 * @property string $description
 * @property integer $cost
 * @property double $discount
 * @property integer $count
 * @property integer $enchant
 * @property integer $user_id
 * @property integer $char_id
 * @property integer $gs_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property AllItems $itemInfo
 * @property Gs $gs
 */
class PurchaseItemsLog extends ActiveRecord
{
    public function tableName()
    {
        return '{{purchase_items_log}}';
    }

    public function relations()
    {
        return [
            'itemInfo' => [self::HAS_ONE, 'AllItems', ['item_id' => 'item_id']],
            'gs' => [self::HAS_ONE, 'Gs', ['id' => 'gs_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pack_id' => 'ID набора',
            'item_id' => 'ID предмета',
            'description' => 'Описание',
            'cost' => 'Стоимость',
            'discount' => 'Скидка на товар',
            'count' => 'Кол-во',
            'enchant' => 'Заточка',
            'user_id' => 'Кто купил',
            'char_id' => 'ID персонажа на сервере',
            'created_at' => 'Дата создания',
        ];
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
