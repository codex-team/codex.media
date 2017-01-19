/**
* Comments module
* @author Ivan Zhuravlev
*/

/* eslint-disable */

var comments = (function () {

    var answer_buttons_ = null,
        comments_list_  = null;

    var nodes_ = {
        form                 : null,
        parent_id            : null,
        root_id              : null,
        add_comment_button   : null,
        add_answer_to        : null,
        cancel_answer_button : null,
        textarea             : ""
    };

    function init () {

        nodes_.form                 = document.getElementById("comment_form");
        nodes_.parent_id            = nodes_.form.parent_id;
        nodes_.root_id              = nodes_.form.root_id;
        nodes_.add_comment_button   = nodes_.form.add_comment_button;
        nodes_.add_answer_to        = document.getElementById('add_answer_to');
        nodes_.cancel_answer_button = document.getElementById('cancel_answer');
        nodes_.textarea             = nodes_.form.add_comment_textarea;
        comments_list_              = document.getElementById('page_comments');
        answer_buttons_             = document.getElementsByClassName('answer_button');

        // Чистим textarea после загрузки страницы
        clearTextarea_();

        // Добавляенм слушатель события ввода в textarea
        nodes_.textarea.addEventListener('input', textareaInputHandler_, false);

        // Добавляем слушатель события клика по "ответить"
        for(var i = 0; i < answer_buttons_.length; i++) {

            answer_buttons_[i].addEventListener('click', replyButtonClickHandler_, false);

        }

        // Добавляем слушатель события клика по крестику
        nodes_.cancel_answer_button.addEventListener('click', canselReplyButtonClickHandler_, false);

        nodes_.textarea.addEventListener('keydown', keydownSubmitHandler_, false);
    }

    /**
     * Очищаем textarea после перезагрузки страницы
     */
    function clearTextarea_ () {

        nodes_.textarea.value = "";
    }

    /**
     * Обработчик клика по крестику
     */
    function canselReplyButtonClickHandler_ () {

        // Вставляем поле ввода комментария под список комментариев
        comments_list_.appendChild(nodes_.form);

        prepareForm_({

            parent_id : 0,
            root_id   : 0
        });
    }

    /**
     * Обработчик клика по "Ответить"
     */
    function replyButtonClickHandler_ (event) {

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

            parent_id : button.dataset.commentId,
            root_id   : button.dataset.rootId,
        });
    }

    /**
     * Принимаем массив settings. Оформляем форму
     */
    function prepareForm_ (settings) {

        // Заполняем hidden поля формы значениями root_id и parent_id или нулями.
        nodes_.parent_id.value = settings.parent_id;
        nodes_.root_id.value   = settings.root_id;

        if (settings.parent_id) {

            // Берем имя автора из соответствующего тега в родительском комментарии
            var parent_author = document.querySelector('#comment_' + settings.parent_id + ' .author_name').innerHTML;
            nodes_.add_answer_to.innerHTML = '<i class="icon-right-dir"></i> ' + parent_author;

            // Изменяем текст кнопки в форме
            nodes_.add_comment_button.value = 'Ответить';

        } else {

            nodes_.add_answer_to.innerHTML = '';

            // Изменяем текст кнопки в форме
            nodes_.add_comment_button.value = 'Оставить комментарий';
        }
    }

    /**
     * Обработчик ввода в textarea
     */
    function textareaInputHandler_ () {

        enableButton_();
    }

    /**
     * Отключаем кнопку submit, если поле пустое или поле только с пробелами
     */
    function enableButton_ () {

        var field_value = nodes_.textarea.value.trim();

        nodes_.add_comment_button.disabled = !field_value;
    }

    /*
     * Если нажаты сочетания Ctrl+Enter или Cmd+Enter, отправляем комментарий
     */
    function keydownSubmitHandler_ (event) {

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
