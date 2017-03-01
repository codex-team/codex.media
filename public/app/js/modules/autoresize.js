module.exports = (function (autoresize) {

    autoresize.textareaClicked = function (event) {

        var textarea = event.target;

        console.log(textarea);

        autoresize.checkScrollHeight(textarea);

    };

    autoresize.init = function () {

        var textareas = document.getElementsByClassName('js-autoresizable');

        if (textareas.length) {

            for (var i = 0; i <= textareas.length - 1; i++) {

                textareas[i].addEventListener('keydown', autoresize.textareaClicked, false);

            }

        }

    };

    autoresize.checkScrollHeight = function (textarea) {

        textarea.style.overflow = 'hidden';

        if (textarea.scrollHeight > textarea.clientHeight) {

            textarea.style.height = textarea.scrollHeight + 'px';

        }

    };

    return autoresize;

}({}));
