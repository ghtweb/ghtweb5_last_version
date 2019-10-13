<?php

/**
 * Class Timer
 *
 * Используется плагин: http://keith-wood.name/countdown.html
 */
class Timer extends CWidget
{
    /**
     * Время окончания
     * @var int
     */
    public $timeEnd;


    public function run()
    {
        Yii::beginProfile('application.widgets.Timer');

        $this->render('timer', [
            'timeEnd' => $this->timeEnd,
        ]);

        Yii::endProfile('application.widgets.Timer');
    }
}
 