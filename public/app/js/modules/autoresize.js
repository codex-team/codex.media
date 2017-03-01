module.exports = (function () {

    var textareaClicked = function (event) {

        var textarea = event.target;

        console.log(textarea);

    };

    var init = function () {

        var textareas = document.getElementsByClassName('js-autoresizable');

        console.log(init);

        if (textareas.length) for (var i = 0; i <= textareas.length - 1; i++) {

            textareas[i].addEventListener('keydown', textareaClicked, false);

        }

    };

    var checkScrollHeight = function (textarea) {

        console.log(checkScrollHeight);

        if (textarea.scrollHeight > textarea.clientHeight) {

            textarea.style.height = textarea.scrollHeight + 'px';

        }

        textarea.style.overflow = 'hidden';

    };

    return {
        textareaClicked : textareaClicked,
        init : init,
        checkScrollHeight : checkScrollHeight
    };

});
