<?php

/**
 * see: https://github.com/Eonasdan/bootstrap-datetimepicker
 * Class DatetimePicker
 */
class DatetimePicker extends CWidget
{
    /**
     * Поля к которым будет применен виджет
     * @var array
     */
    public $fields = [];

    /**
     * @var array
     */
    public $defaultParams = [
        'format' => 'YYYY-MM-DD', // НЕ менять, блиать!, формат для даты и времени: YYYY-MM-DD HH:mm:ss (HH - не трогать иначе формат даты будет 12 часов + am/pm)
    ];

    /**
     * @var array
     */
    public $params = [];


    public function run()
    {
        if (!$this->fields) {
            return;
        }

        $this->params = CMap::mergeArray($this->defaultParams, $this->params);

        Yii::app()->clientScript->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css');
        Yii::app()->clientScript->registerScriptFile('//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/ru.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js', CClientScript::POS_END);

        $scriptUniqueId = 'bootstrap-datepicker' . implode('', $this->fields);

        Yii::app()->clientScript->registerScript($scriptUniqueId, '$("' . implode(',', $this->fields) . '").datetimepicker(' . $this->getParams() . ');');
    }

    private function getParams()
    {
        $params = '{';

        foreach ($this->params as $k => $v) {
            $v = (is_string($v) ? "'" . $v . "'" : $v);
            $v = (is_bool($v) ? strtolower(($v ? 'true' : 'false')) : $v);

            $params .= "'$k':" . $v . ",";
        }

        return substr($params, 0, -1) . '}';
    }
}
 