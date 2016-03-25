var Comments = {
    
    answer_button : null,
    comment_body : null,
    comments_list : null,
    
    form : {
        wrapper : null,
        parent_id : null,
        root_id : null,    
        add_comment_button : null,
        add_answer_to : null,
        cancel_answer_button : null,
        add_comment_field : ""
    },
    
    init : function() {
        this.form.wrapper              = document.getElementById("comment_form");
        this.form.parent_id            = document.getElementById("parent_id");
        this.form.root_id              = document.getElementById("root_id");  
        this.form.add_comment_button   = document.getElementById("add_comment_button");
        this.form.add_answer_to        = document.getElementById("add_answer_to");
        this.form.cancel_answer_button = document.getElementById("cancel_answer");
        this.form.add_comment_field    = document.getElementById("add_comment_field");
        
        this.comments_list             = document.getElementById('page_comments');
    },
    
    /**
     * Очищаем textarea после перезагрузки страницы
     */
    clear_textarea: function() {
        this.form.add_comment_field.value = "";
    },

    /**
     * Делаем комментарий ответом
     */
    answer: function(comment_id, root_id, author) {
        this.answer_button = document.getElementById('answer_button_' + comment_id);
        this.comment_body  = document.getElementById('comment_' + comment_id);
        
        var _this = this;
        
        this.answer_button.addEventListener('click', function() {
            
            // Заполняем hidden поля формы значениями root_id и parent_id текущего комментария.
            _this.form.parent_id.value = comment_id;
            _this.form.root_id.value   = root_id;
            
            // Оформляем форму комментария как ответ
            _this.form.add_comment_button.value       = 'Ответить';
            _this.form.add_answer_to.innerHTML        = 'пользователю ' + '<b>' + author + '</b>';
            _this.form.cancel_answer_button.innerHTML = '<i class="icon-cancel"></i>';
            
            // Сжимаем текстовое поле формы комментария, переводим фокус на него
            _this.form.add_comment_field.rows = 4;
            _this.form.add_comment_field.focus();
            
            // Перемещаем форму под текущий комментарий, подгоняем под его ширину
            _this.form.wrapper.classList.add('answer_form');
            _this.comment_body.appendChild(_this.form.wrapper);
        }, false);
    },

    /**
     * Делаем ответ комментарием
     */
    close_answer: function() {
        var _this = this;
        
        this.form.cancel_answer_button.addEventListener('click', function() {
            // Убираем значения root_id и parent_id из hidden полей 
            _this.form.parent_id.value = 0;
            _this.form.root_id.value   = 0;
            
            // Возвращаем исходное оформление комментария
            _this.form.add_comment_button.value       = 'Оставить комментарий';
            _this.form.add_answer_to.innerHTML        = '';
            _this.form.cancel_answer_button.innerHTML = '';
            
            // Делаем высоту текстового поля стандартной
            _this.form.add_comment_field.rows = 6;
            
            // Возвращаем форму под список комментариев, возвращаем исходную ширину
            _this.form.wrapper.classList.remove('answer_form');
            _this.comments_list.appendChild(_this.form.wrapper);
        }, false);
    },

    /**
     * Отключаем кнопку submit, если поле пустое или поле только с пробелами
     */
    enable_button: function() {
        var _this = this;

        this.form.add_comment_field.addEventListener('input', function() {
            var field_value = _this.form.add_comment_field.value.trim();

            if (field_value) {
                _this.form.add_comment_button.disabled = false;
            } else {
                _this.form.add_comment_button.disabled = true;
            }            
        }, false);
    }
};
