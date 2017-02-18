/**
* Comments module
* @author Ivan Zhuravlev
*/

var comments = (function () {

    var answerButtons = null,
        commentsList  = null;

    var nodes_ = {
        form               : null,
        parentId           : null,
        rootId             : null,
        addCommentButton   : null,
        addAnswerTo        : null,
        cancelAnswerButton : null,
        textarea           : ''
    };

    function init() {

        nodes_.form               = document.getElementById('comment_form');
        nodes_.parentId           = nodes_.form.parentId;
        nodes_.rootId             = nodes_.form.rootId;
        nodes_.addCommentButton   = nodes_.form.addCommentButton;
        nodes_.addAnswerTo        = document.getElementById('addAnswerTo');
        nodes_.cancelAnswerButton = document.getElementById('cancel_answer');
        nodes_.textarea           = nodes_.form.add_comment_textarea;
        commentsList              = document.getElementById('page_comments');
        answerButtons             = document.getElementsByClassName('comment-actions__button--answer');

        // Чистим textarea после загрузки страницы
        clearTextarea_();

        // Добавляенм слушатель события ввода в textarea
        nodes_.textarea.addEventListener('input', textareaInputHandler_, false);

        // Добавляем слушатель события клика по "ответить"
        for (var i = 0; i < answerButtons.length; i++) {

            answerButtons[i].addEventListener('click', replyButtonClickHandler_, false);

        }

        // Добавляем слушатель события клика по крестику
        nodes_.cancelAnswerButton.addEventListener('click', canselReplyButtonClickHandler_, false);

        nodes_.textarea.addEventListener('keydown', keydownSubmitHandler_, false);

    }

    /**
     * Очищаем textarea после перезагрузки страницы
     */
    function clearTextarea_() {

        nodes_.textarea.value = '';

    }

    /**
     * Обработчик клика по крестику
     */
    function canselReplyButtonClickHandler_() {

        // Вставляем поле ввода комментария под список комментариев
        commentsList.appendChild(nodes_.form);

        prepareForm_({
            parentId : 0,
            rootId   : 0
        });

    }

    /**
     * Обработчик клика по "Ответить"
     */
    function replyButtonClickHandler_(event) {

        var button, comment;

        /**
         * Проверяем, является ли кнопкой то, на что нажал пользователь (у кнопки есть дата-атрибут)
         * Если да, то обращаемся к кнопке напрямую, через event.target
         * Если нет (т.е. кликнули на иконку), то - через event.target.parentNode
         */
        if (event.target.dataset.commentId) {

            button = event.target;

        } else {

            button = event.target.parentNode;

        }

        comment = document.getElementById('comment_' + button.dataset.commentId);

        // Вставляем поле добавления комментария под нужный комментарий
        comment.appendChild(nodes_.form);

        prepareForm_({
            parentId : button.dataset.commentId,
            rootId   : button.dataset.rootId,
        });

    }

    /**
     * Принимаем массив settings. Оформляем форму
     */
    function prepareForm_(settings) {

        // Заполняем hidden поля формы значениями rootId и parentId или нулями.
        nodes_.parentId.value = settings.parentId;
        nodes_.rootId.value   = settings.rootId;

        if (settings.parentId) {

            // Берем имя автора из соответствующего тега в родительском комментарии
            var parentAuthor = document.querySelector('#comment_' + settings.parentId + ' .author_name').innerHTML;

            nodes_.addAnswerTo.innerHTML = '<i class="icon-right-dir"></i> ' + parentAuthor;

            // Изменяем текст кнопки в форме
            nodes_.addCommentButton.value = 'Ответить';

        } else {

            nodes_.addAnswerTo.innerHTML = '';

            // Изменяем текст кнопки в форме
            nodes_.addCommentButton.value = 'Оставить комментарий';

        }

    }

    /**
     * Обработчик ввода в textarea
     */
    function textareaInputHandler_() {

        enableButton_();

    }

    /**
     * Отключаем кнопку submit, если поле пустое или поле только с пробелами
     */
    function enableButton_() {

        var fieldValue = nodes_.textarea.value.trim();

        nodes_.addCommentButton.disabled = !fieldValue;

    }

    /*
     * Если нажаты сочетания Ctrl+Enter или Cmd+Enter, отправляем комментарий
     */
    function keydownSubmitHandler_(event) {

        var CtrlPressed  = event.ctrlKey || event.metaKey,
            EnterPressed = event.keyCode == 13;

        if ( CtrlPressed && EnterPressed ) {

            nodes_.form.submit();

        }

    }

    return {
        init: init
    };

}());

module.exports = comments;
