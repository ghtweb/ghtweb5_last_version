<?php

class NewsSocials extends CWidget
{
    /**
     * @var array
     */
    public $params;

    /**
     * @var int
     */
    public $charLimit = 300;


    public function init()
    {
        Yii::beginProfile(__CLASS__);

        $path = Yii::getPathOfAlias('app.widgets.NewsSocials.libs.share42');

        $assetsUrl = app()->getAssetManager()->publish($path, false, -1, YII_DEBUG);

        js($assetsUrl . '/share42.js', CClientScript::POS_END);

        // Обрезаю description
        if (!empty($this->params['data-description'])) {
            $this->params['data-description'] = strip_tags($this->params['data-description']);

            if (mb_strlen($this->params['data-description']) > $this->charLimit) {
                $this->params['data-description'] = characterLimiter($this->params['data-description'], $this->charLimit, ' ...');
            }
        }

        echo CHtml::openTag('div', $this->params + ['class' => 'share42init']) . CHtml::closeTag('div');

        Yii::endProfile(__CLASS__);
    }
}
 