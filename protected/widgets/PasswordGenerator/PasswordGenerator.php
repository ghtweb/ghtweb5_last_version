<?php

/**
 * Виджет генерации пароля для формы регистрации
 *
 * Class PasswordGenerator
 */
class PasswordGenerator extends CWidget
{
    /**
     * Текст на кнопке
     * @var string
     */
    public $text;

    /**
     * Мин. длина пароля
     * @var int
     */
    public $minLength = 6;

    /**
     * Макс. длина пароля
     * @var int
     */
    public $maxLength = 10;


    public function run()
    {
        if (!$this->text) {
            $this->text = 'сгенерировать пароль';
        }

        $assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.widgets.' . __CLASS__ . '.assets'), false, -1, YII_DEBUG);

        Yii::app()->clientScript->registerScript('passwordGenerator', '
        var passwordGeneratorPasswordMaxLength = ' . $this->maxLength . ',
            passwordGeneratorPasswordMinLength = ' . $this->minLength . ';', CClientScript::POS_END);

        Yii::app()->clientScript->registerScriptFile($assetsUrl . '/js/password-generator.js', CClientScript::POS_END);
        echo '<a class="js-password-generator">' . $this->text . '</a>';
    }
}
