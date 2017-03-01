module.exports = (function () {

    var textareaClicked = function (event) {

        var textarea = event.target;

    };

    var init = function () {

        var textareas = document.getElementsByClassName('js-autoresizable');

        if (textareas.length) for (var i = 0; i <= textareas.length - 1; i++) {

            textareas[i].addEventListener('keydown', textareaClicked, false);

        }

    };

    var checkScrollHeight = function (textarea) {

        if (textarea.scrollHeight > textarea.clientHeight) {

            textarea.style.height = textarea.scrollHeight + 'px';

        }

        textarea.style.overflow = 'hidden';

    };

});
