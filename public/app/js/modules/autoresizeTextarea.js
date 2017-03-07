/**
* Module for comments textarea autoresize
*/
module.exports = (function () {

    /**
    * Textareas initialization
    */
    var init = function () {

        var textareas = document.getElementsByClassName('js-autoresizable');

        if (textareas.length) {

            for (var i = 0; i < textareas.length; i++) {

                addListener(textareas[i]);

            }

        }

    };

    /**
    * Add input event listener to textarea
    *
    * @param {Element} textarea — node which need to be able to autoresize
    */
    var addListener = function (textarea) {

        textarea.addEventListener('input', textareaChanged, false);

    };

    /**
    * Hanging events on textareas
    */
    var textareaChanged = function (event) {

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

    };

    return {
        init: init,
        addListener : addListener
    };

}());
