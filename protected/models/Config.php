<?php

/**
 * This is the model class for table "{{config}}".
 *
 * The followings are the available columns in table '{{config}}':
 * @property string $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $label
 * @property string $type
 *
 * The followings are the available model relations:
 * @property ConfigGroup $group
 */
class Config extends ActiveRecord
{
    public function tableName()
    {
        return '{{config}}';
    }

    public function relations()
    {
        return ['group' => [self::BELONGS_TO, 'ConfigGroup', 'id']];
    }

    public function getField()
    {
        switch ($this->field_type) {
            case 'dropDownList':
                if ($this->method && method_exists($this, $this->method)) {
                    $params = $this->{$this->method}();
                } else {
                    $params = ['Нет', 'Да'];
                }

                $htmlOptions = ['class' => 'form-control'];

                $field = CHtml::dropDownList('Config[' . $this->param . ']', ($this->value == '' ? $this->default : $this->value), $params, $htmlOptions);

                break;
            default:
                $field = CHtml::textField('Config[' . $this->param . ']', ($this->value == '' ? $this->default : $this->value), ['class' => 'form-control']);
        }

        return $field;
    }

    /**
     * Список GS
     *
     * @return array
     */
    public function getGs()
    {
        return CHtml::listData(Gs::model()->findAll(), 'id', 'name');
    }

    /**
     * Возвращает типы главной страницы
     *
     * @return array
     */
    public function getIndexPageTypes()
    {
        return [
            'page' => 'Страница',
            'news' => 'Новости',
            'rss' => 'RSS новости с форума',
        ];
    }

    /**
     * Список страниц
     *
     * @return array
     */
    public function getPages()
    {
        return CHtml::listData(Pages::getOpenList(), 'page', 'title');
    }

    /**
     * Список платежных систем
     *
     * @return array
     */
    public function getPaymentTypes()
    {
        Yii::import('modules.deposit.extensions.Deposit.Deposit');
        $data = Deposit::getAggregatorsList();

        unset($data[Deposit::PAYMENT_SYSTEM_UNITPAY_SMS]);

        return $data;
    }

    /**
     * Возвращает типы форумов
     *
     * @return array
     */
    public function getForumTypes()
    {
        $types = app()->params['forum_types'];

        return array_combine($types, $types);
    }

    /**
     * Возвращает темы
     *
     * @return array
     */
    public function getThemes()
    {
        return getTemplates();
    }
}