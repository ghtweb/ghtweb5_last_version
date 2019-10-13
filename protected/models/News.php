<?php

use app\components\behaviors\image\ImgData;

/**
 * This is the model class for table "{{news}}".
 *
 * The followings are the available columns in table '{{news}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $img
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Admin $author
 */
class News extends ActiveRecord
{
    const IMAGES_PATH = 'images/news';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ImageBehavior'] = [
            'class' => 'application.components.behaviors.image.ImageBehavior',
            'uploadPath' => app()->params['uploadPath'] . '/' . self::IMAGES_PATH,
            'imgData' => [
                new ImgData('original', config('news.img.width'), config('news.img.height')),
            ],
        ];

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => News::class,
            'idAttributeName' => 'slug',
        ];

        return $behaviors;
    }

    public function tableName()
    {
        return '{{news}}';
    }

    public static function getUploadPath()
    {
        return Yii::getPathOfAlias('webroot') . '/' . app()->params['uploadPath'] . '/images/news';
    }

    public function getDate()
    {
        return $this->getCreatedAt(config('news.date_format'));
    }

    public function relations()
    {
        return [
            'author' => [self::HAS_ONE, Admin::class, ['id' => 'user_id']],
        ];
    }
}
