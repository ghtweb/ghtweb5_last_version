<?php

use app\components\behaviors\image\ImgData;
use Intervention\Image\ImageManagerStatic;

class ImageBehavior extends CActiveRecordBehavior
{
    /**
     * Аттрибут с картинкой
     * @var string
     */
    public $attributeName = 'img';

    /**
     * Путь куда сохранять картинки
     * @var string
     */
    public $uploadPath;

    /**
     * @var ImgData[]
     */
    public $imgData;

    /**
     * @var string|callable
     */
    public $imgName;

    /**
     * Текущая картинка из модели
     * @var string
     */
    protected $img;

    protected $images = [];

    private $isDelImg = false;

    public function afterFind($event)
    {
        $this->img = $this->owner->{$this->attributeName};

        foreach ($this->imgData as $row) {
            $imgInfo = pathinfo($this->img);
            if (!isset($imgInfo['extension'])) {
                continue;
            }
            $img = $imgInfo['filename'] . $row->getName() . '.' . $imgInfo['extension'];
            $this->images[$row->getName()] = [
                'path' => $this->getRoot() . '/' . $this->getUploadPath() . '/' . $img,
                'url' => app()->createAbsoluteUrl($this->getUploadPath()) . $img,
                'fileName' => $img,
            ];
        }
    }

    public function beforeSave($event)
    {
        if ($event->sender->{$this->attributeName} instanceof CUploadedFile) {
            $this->saveImage($event->sender->{$this->attributeName}, $event->sender);
        } elseif ($this->isDelImg === false) {
            $event->sender->{$this->attributeName} = $this->img;
        } elseif ($this->isDelImg === true) {
            $event->sender->{$this->attributeName} = null;
        }
    }

    protected function saveImage(CUploadedFile $file, $model)
    {
        if (!is_array($this->imgData) || count($this->imgData) == 0) {
            return;
        }

        $imgName = $this->getImgName();
        $root = $this->getRoot();

        foreach ($this->imgData as $imgData) {
            ImageManagerStatic::make($file->tempName)
                ->resize($imgData->getWidth(), $imgData->getHeight(), function($constraint){
                    $constraint->aspectRatio();
                })
                ->save($root . '/' . $this->getUploadPath() . '/' . $imgName . $imgData->getName() . '.' . $file->extensionName, $imgData->getQuality());
        }

        $this->deleteImage();

        $model->{$this->attributeName} = $imgName . '.' . $file->extensionName;
    }

    protected function getImgName()
    {
        return is_callable($this->imgName)
            ? call_user_func($this->imgName)
            : md5(microtime(true) . rand());
    }

    protected function getRoot()
    {
        return Yii::getPathOfAlias('webroot');
    }

    public function imgIsExists($name)
    {
        if (!isset($this->images[$name]['path'])) {
            return false;
        }

        return is_file($this->getImgPath($name));
    }

    public function getImgUrl($name)
    {
        return $this->images[$name]['url'];
    }

    public function getImgPath($name)
    {
        return $this->images[$name]['path'];
    }

    public function getUploadPath()
    {
        return trim($this->uploadPath, '/');
    }

    public function afterDelete($event)
    {
        $this->deleteImage();
    }

    protected function deleteImage()
    {
        $this->isDelImg = true;

        foreach ($this->images as $image) {
            if (is_file($image['path'])) {
                unlink($image['path']);
            }
        }
    }
}
