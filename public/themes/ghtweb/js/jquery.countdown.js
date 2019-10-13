(function($){
    'use strict';

    $.fn.countdown = function(options){

        options = $.extend({
            currentTime: null,
            stopTime: null,
            onExpiry: null
        },options);

        // Helpers
        var floor = function(a){
            return Math.floor(a);
        };

        var leadZero = function(number, length){
            while(number.toString().length < length){
                number = '0' + number;
            }
            return number;
        }

        var wrap = function(str){
            var wrap_ = '';
            $.each(str.toString().split(''),function(index,number){
                wrap_ += '<span>' + number + '</span>';
            });
            return wrap_;
        };

        var plural = function(number, one, two, five) {
            number = Math.abs(number);
            number %= 100;

            if (number >= 5 && number <= 20) {
                return five;
            }

            number %= 10;
            if (number == 1) {
                return one;
            }

            if (number >= 2 && number <= 4) {
                return two;
            }

            return five;
        }

        var html = $('<div class="countdown">\
                    <span class="name">До открытия осталось:</span>\
                    <ul>\
                        <li class="days">\
                            <span class="count"></span>\
                            <span class="type"></span>\
                        </li>\
                        <li class="divider">:</li>\
                        <li class="hours">\
                            <span class="count"></span>\
                            <span class="type"></span>\
                        </li>\
                        <li class="divider">:</li>\
                        <li class="minutes">\
                            <span class="count"></span>\
                            <span class="type"></span>\
                        </li>\
                        <li class="divider">:</li>\
                        <li class="seconds">\
                            <span class="count"></span>\
                            <span class="type"></span>\
                        </li>\
                    </ul>\
                </div>');

        var init = function(){
            
            if(options.stopTime < options.currentTime){
                return;
            }

            var $timer  = $(this),
                $timerD = html.find('.days .count'),
                $timerH = html.find('.hours .count'),
                $timerM = html.find('.minutes .count'),
                $timerS = html.find('.seconds .count'),
                $typeD  = html.find('.days .type'),
                $typeH  = html.find('.hours .type'),
                $typeM  = html.find('.minutes .type'),
                $typeS  = html.find('.seconds .type'),
                ct      = options.currentTime,
                st      = options.stopTime;

            var timer_ = setInterval(function(){

                ct++;

                var a1   = st - ct,
                    min  = 60,
                    hour = 60 * 60,
                    day  = 60 * 60 * 24;

                var r_day  = leadZero(floor(a1 / day), 2),
                    r_hour = leadZero(floor((a1 - (r_day * day)) / hour), 2),
                    r_min  = leadZero(floor((a1 - (r_day * day) - (r_hour * hour)) / min), 2),
                    r_sec  = leadZero(a1 - (r_day * day) - (r_hour * hour) - (r_min * min), 2);

                var t_day  = plural(r_day, 'день', 'дня', 'дней'),
                    t_hour = plural(r_hour, 'час', 'часа', 'часов'),
                    t_min  = plural(r_min, 'минута', 'минуты', 'минут'),
                    t_sec  = plural(r_sec, 'секунда', 'секунды', 'секунд');

                //console.warn(['days', r_day], ['hour', r_hour], ['min', r_min], ['sec', r_sec]);

                // Days
                $timerD.html(r_day);
                $typeD.html(t_day);

                // Hour
                $timerH.html(r_hour);
                $typeH.html(t_hour);

                // Min
                $timerM.html(r_min);
                $typeM.html(t_min);

                // Sec
                $timerS.html(r_sec);
                $typeS.html(t_sec);

                $timer.html(html);

                if(ct >= st){
                    clearInterval(timer_);
                    if(typeof options.onExpiry == 'function'){
                        options.onExpiry();
                    }
                }

            }, 1000);
        };

        return this.each(init);

    };

})(jQuery);