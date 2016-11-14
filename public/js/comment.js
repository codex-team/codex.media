var Comments = {

    answer_buttons : null,
    comments_list  : null,

    nodes : {
        form                 : null,
        parent_id            : null,
        root_id              : null,
        add_comment_button   : null,
        add_answer_to        : null,
        cancel_answer_button : null,
        textarea             : ""
    },

    init : function() {

        this.nodes.form                 = document.getElementById("comment_form");
        this.nodes.parent_id            = this.nodes.form.parent_id;
        this.nodes.root_id              = this.nodes.form.root_id;
        this.nodes.add_comment_button   = this.nodes.form.add_comment_button;
        this.nodes.add_answer_to        = document.getElementById('add_answer_to');
        this.nodes.cancel_answer_button = document.getElementById('cancel_answer');
        this.nodes.textarea             = this.nodes.form.add_comment_textarea;
        this.comments_list              = document.getElementById('page_comments');
        this.answer_buttons             = document.getElementsByClassName('answer_button');

        // Чистим textarea после загрузки страницы
        this.clearTextarea();

        // Добавляенм слушатель события ввода в textarea
        this.nodes.textarea.addEventListener('input', Comments.textareaInputHandler, false);

        // Добавляем слушатель события клика по "ответить"
        for(var i = 0; i < this.answer_buttons.length; i++) {

            this.answer_buttons[i].addEventListener('click', function(event) {

                Comments.replyButtonClickHandler(event);

            }, false);
        }

        // Добавляем слушатель события клика по крестику
        this.nodes.cancel_answer_button.addEventListener('click', function() {

            Comments.canselReplyButtonClickHandler();

        }, false);

        this.nodes.textarea.addEventListener('keydown', function (event) {

            Comments.keydownSubmitHandler(event);

        }, false);
    },

    /**
     * Очищаем textarea после перезагрузки страницы
     */
    clearTextarea : function() {

        this.nodes.textarea.value = "";
    },

    /**
     * Обработчик клика по крестику
     */
    canselReplyButtonClickHandler : function() {

        // Вставляем поле ввода комментария под список комментариев
        this.comments_list.appendChild(this.nodes.form);

        this.prepareForm({

            parent_id : 0,
            root_id   : 0
        });
    },

    /**
     * Обработчик клика по "Ответить"
     */
    replyButtonClickHandler : function(event) {

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
        comment.appendChild(this.nodes.form);

        this.prepareForm({

            parent_id : button.dataset.commentId,
            root_id   : button.dataset.rootId,
        });
    },

    /**
     * Принимаем массив settings. Оформляем форму
     */
    prepareForm : function(settings) {

        // Заполняем hidden поля формы значениями root_id и parent_id или нулями.
        this.nodes.parent_id.value = settings.parent_id;
        this.nodes.root_id.value   = settings.root_id;

        if (settings.parent_id) {

            // Берем имя автора из соответствующего тега в родительском комментарии
            var parent_author = document.querySelector('#comment_' + settings.parent_id + ' .author_name').innerHTML;
            this.nodes.add_answer_to.innerHTML = '<i class="icon-right-dir"></i> ' + parent_author;

            // Изменяем текст кнопки в форме
            this.nodes.add_comment_button.value = 'Ответить';

        } else {

            this.nodes.add_answer_to.innerHTML = '';

            // Изменяем текст кнопки в форме
            this.nodes.add_comment_button.value = 'Оставить комментарий';
        }
    },

    /**
     * Обработчик ввода в textarea
     */
    textareaInputHandler : function() {

        Comments.enableButton();
    },

    /**
     * Отключаем кнопку submit, если поле пустое или поле только с пробелами
     */
    enableButton : function() {

        var field_value = this.nodes.textarea.value.trim();

        this.nodes.add_comment_button.disabled = !field_value;
    },

    /*
     * Если нажаты сочетания Ctrl+Enter или Cmd+Enter, отправляем комментарий
     */
    keydownSubmitHandler : function(event) {

        var CtrlPressed  = event.ctrlKey || event.metaKey,
            EnterPressed = event.keyCode == 13;

        if ( CtrlPressed && EnterPressed ) {

            this.nodes.form.submit();
        }
    }
};

codex.documentIsReady(function(){

    Comments.init();

});
