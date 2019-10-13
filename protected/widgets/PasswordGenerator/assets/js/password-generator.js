(function () {

    'use strict';

    var generator, maxLength, rand;

    /**
     * Аналог PHP функции rand
     *
     * @param {int} min
     * @param {int} max
     *
     * @returns {string}
     */
    rand = function (min, max) {
        if (max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        } else {
            return Math.floor(Math.random() * (min + 1));
        }
    }

    /**
     * Генератор пароля
     *
     * @param {int} length
     *
     * @return {string}
     */
    generator = function (length) {
        var result = '',
            words = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM',
            max_position = words.length - 1,
            i = 0;

        for (; i < 10; ++i) {
            var position = Math.floor(Math.random() * max_position);
            result = result + words.substring(position, position + 1);
        }

        return result;
    };

    $(function () {
        $('.js-password-generator').on('click', function (e) {
            e.preventDefault();

            var $self = $(this),
                pass = generator(rand(passwordGeneratorPasswordMinLength, passwordGeneratorPasswordMaxLength));

            if (prompt("Запомните или запишите его.", pass)) {
                $('#RegisterForm_password').val(pass);
                $('#RegisterForm_re_password').val(pass);
            }
        });
    });

})();