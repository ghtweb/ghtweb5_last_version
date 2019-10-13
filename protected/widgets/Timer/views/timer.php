<?php
/**
 * @var Timer $this
 * @var int $timeEnd
 */
$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.widgets.Timer.assets'), false, -1, YII_DEBUG);

$langFile = $assetsUrl . '/js/jquery.countdown-' . app()->getLanguage() . '.js';

js($assetsUrl . '/js/jquery.plugin.min.js', CClientScript::POS_END);
js($assetsUrl . '/js/jquery.countdown.min.js', CClientScript::POS_END);

if (is_file($langFile)) {
    js($langFile, CClientScript::POS_END);
}

css($assetsUrl . '/css/jquery.countdown.css');

app()->getClientScript()->registerScript('timer', "
$(function(){
    $('.ghtweb-timer').countdown({
        until: (new Date(" . $timeEnd . "000)),
        layout: '<ul>{y<}<li class=\"years\">{yn} <span>{yl}</span></li>{y>}{o<}<li class=\"month\">{on} <span>{ol}</span></li>{o>}' +
            '{d<}<li class=\"days\">{dn} <span>{dl}</span></li>{d>}{h<}<li class=\"hours\">{hn} <span>{hl}</span></li>{h>}' +
            '{m<}<li class=\"minutes\">{mn} <span>{ml}</span></li>{m>}{s<}<li class=\"seconds\">{sn} <span>{sl}</span></li>{s>}</ul>',
        onExpiry: function(){
            console.log('onExpiry');
        }
    });
});", CClientScript::POS_END);
?>

<div class="ghtweb-timer"></div>
