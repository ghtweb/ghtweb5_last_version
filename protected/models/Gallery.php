<?php

use app\components\behaviors\image\ImgData;

/**
 * This is the model class for table "{{gallery}}".
 *
 * The followings are the available columns in table '{{gallery}}':
 * @property string $id
 * @property string $img
 * @property integer $status
 * @property integer $sort
 * @property string $created_at
 * @property string $updated_at
 */
class Gallery extends ActiveRecord
{
    const IMAGES_PATH = 'images/gallery';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ImageBehavior'] = [
            'class' => 'application.components.behaviors.image.ImageBehavior',
            'uploadPath' => app()->params['uploadPath'] . '/' . self::IMAGES_PATH,
            'imgData' => [
                new ImgData('original', config('gallery.big.width'), config('gallery.big.height')),
                new ImgData('thumb', config('gallery.small.width'), config('gallery.small.height'))
            ],
        ];

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Gallery::class,
        ];

        return $behaviors;
    }

    public function tableName()
    {
        return '{{gallery}}';
    }

    public static function getUploadPath()
    {
        return Yii::getPathOfAlias('webroot') . '/' . app()->params['uploadPath'] . '/images/gallery';
    }
}
