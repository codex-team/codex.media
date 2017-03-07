/**
* Module for comments textarea autoresize
*/
module.exports = (function () {

    /**
    * Textareas initialization
    */
    function init() {

        var textareas = document.getElementsByClassName('js-autoresizable');

        if (textareas.length) {

            for (var i = 0; i < textareas.length; i++) {

                textareas[i].addEventListener('input', textareaClicked, false);

            }

        }

    };

    /**
    * Hanging events on textareas
    */
    var textareaClicked = function (event) {

        var textarea = event.target;

        checkScrollHeight(textarea);

    };

    /**
    * Increasing textarea height
    */
    var checkScrollHeight = function (textarea) {

        if (textarea.scrollHeight > textarea.clientHeight) {

            textarea.style.height = textarea.scrollHeight + 'px';

        }

        return {
            init: init
        };

    };

}());
