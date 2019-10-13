(function(){

    'use strict';

    var Message, fancyboxSettings;

    Message = function(type, message, title){
        $.notification({timeout: 5000, title: title, content: message, type: type, img: ""});
    };

    fancyboxSettings = {
        padding: 0
    };


    $(function(){

        // Chosen
        if($.isFunction($.fn.chosen)) {
            $('select:not(.not-chosen)').chosen({
                disable_search_threshold: 10
            });
        }

        // Tooltip
        if($.isFunction($.fn.tooltip)) {
            $('[rel=tooltip]').tooltip();
        }

        // jScrollPane
        if($.isFunction($.fn.jScrollPane)) {
            $('.scroll-pane').jScrollPane();
        }

        // Fancybox
        if($.isFunction($.fn.fancybox)) {
            $('.fancybox').fancybox(fancyboxSettings);
        }

        $('input.js-amount').on('keyup', function() {

            var $self = $(this),
                count = +$self.val().replace( /[^\d]/g , ''),
                $tr = $self.closest('tr'),
                cost = +$tr.find('.js-cost').data('cost'),
                discount = +$tr.find('.js-cost').data('discount'),
                $costh = $tr.find('.js-cost-h'),
                $discounth = $tr.find('.js-discount-h');

            if (count <= 0) {
                count = 1;
            }

            $self.val(count);

            var total = count * cost;

            $costh.text(total);

            if (discount > 0) {
                $discounth.text(total - (total * (discount / 100)));
            }

        });

    });

})();