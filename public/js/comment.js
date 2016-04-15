var Comments = {

    answer_buttons : null,
    comments_list : null,

    nodes : {
        form : null,
        parent_id : null,
        root_id : null,
        add_comment_button : null,
        add_answer_to : null,
        cancel_answer_button : null,
        add_comment_textarea : ""
    },

    init : function() {
        this.form.form                 = document.getElementById("comment_form");
        this.form.parent_id            = this.form.form.parent_id;
        this.form.root_id              = this.form.form.root_id;
        this.form.add_comment_button   = this.form.form.add_comment_button;
        this.form.add_answer_to        = document.getElementById('add_answer_to');
        this.form.cancel_answer_button = document.getElementById('cancel_answer');
        this.form.textarea = this.form.form.add_comment_textarea;

        this.comments_list             = document.getElementById('page_comments');
        this.answer_buttons            = document.getElementsByClassName('answer_button');

        // Чистим textarea после загрузки страницы
        this.clearTextarea();

        // Добавляенм слушатель события ввода в textarea
        this.form.add_comment_textarea.addEventListener('input', Comments.textareaInputHandler, false);

        // Добавляем слушатель события клика по "ответить"
        for(var i = 0; i < this.answer_buttons.length; i++) {

            this.answer_buttons[i].addEventListener('click', function(event) {

                Comments.replyButtonClickHandler(event);

            }, false);

        }

        // Добавляем слушатель события клика по крестику
        this.form.cancel_answer_button.addEventListener('click', function() {
            Comments.canselReplyButtonClickHandler();
        }, false);

        this.form.add_comment_textarea.addEventListener('keydown', function (event) {
            Comments.keydownSubmitHandler(event);
        }, false);

    },

    /**
     * Очищаем textarea после перезагрузки страницы
     */
    clearTextarea : function() {

        this.form.add_comment_textarea.value = "";

    },

    /**
     * Обработчик клика по крестику
     */
    canselReplyButtonClickHandler : function() {

        // Вставляем поле ввода комментария под список комментариев
        this.comments_list.appendChild(this.form.form);

        this.prepareForm({
            parent_id : 0,
            root_id : 0
        });

    },

    /**
     * Обработчик клика по "Ответить"
     */
    replyButtonClickHandler : function(event){

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
        comment.appendChild(this.form.form);

        this.prepareForm({
            parent_id : button.dataset.commentId,
            root_id : button.dataset.rootId,
        });

    },

    /**
     * Принимаем массив settings. Оформляем форму
     */
    prepareForm : function(settings) {
        // Заполняем hidden поля формы значениями root_id и parent_id или нулями.
        this.form.parent_id.value = settings.parent_id;
        this.form.root_id.value   = settings.root_id;

        if (settings.parent_id) {
            // Берем имя автора из соответствующего тега в родительском комментарии
            var parent_author = document.querySelector('#comment_' + settings.parent_id + ' .author_name').innerHTML;
            this.form.add_answer_to.innerHTML = '<i class="icon-right-dir"></i> ' + parent_author;

            // Изменяем текст кнопки в форме
            this.form.add_comment_button.value = 'Ответить';
        } else {
            this.form.add_answer_to.innerHTML = '';

            // Изменяем текст кнопки в форме
            this.form.add_comment_button.value = 'Оставить комментарий';
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
        var field_value = this.form.add_comment_textarea.value.trim();

        this.form.add_comment_button.disabled = !field_value;
    },

    keydownSubmitHandler : function(event) {
        /*
         * Если нажаты сочетания Ctrl+Enter или Cmd+Enter, отправляем комментарий
         */
        if ( (event.ctrlKey || event.metaKey) && event.keyCode == 13 ) {
            this.form.form.submit();
        }
    }
};
