<?php

/**
 * This is the model class for table "{{all_items}}".
 *
 * The followings are the available columns in table '{{all_items}}':
 * @property string $item_id
 * @property string $name
 * @property string $add_name
 * @property string $description
 * @property string $icon
 * @property integer $crystal_type
 */
class AllItems extends ActiveRecord
{
    const ITEM_ICONS_PATH = 'images/items';
    const ITEM_GRADE_ICONS_PATH = 'images/grade';


    public function primaryKey()
    {
        return 'item_id';
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{all_items}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => AllItems::class,
        ];

        return $behaviors;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'ID',
            'name' => 'Название',
            'add_name' => 'Специальное умение',
            'description' => 'Описание',
            'icon' => 'Иконка',
            'crystal_type' => 'Грейд',
        ];
    }

    public function getIcon()
    {
        return Lineage::getItemIcon($this->icon, $this->description);
    }

    public function getGrade()
    {
        return Lineage::getItemGrade($this->crystal_type);
    }

    public function getFullName()
    {
        return $this->name . ($this->add_name ? ' (' . $this->add_name . ')' : '');
    }
}
