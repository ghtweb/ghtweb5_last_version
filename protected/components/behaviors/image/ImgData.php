<?php

namespace app\components\behaviors\image;

class ImgData
{
    private $name;
    private $width;
    private $height;
    private $quality;

    public function __construct($name, $width, $height, $quality = 70)
    {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }
}
