var comments = (function() {
    return {
        /**
         *  Очищаем textarea после перезагрузки
         */

        clear_textarea: function() {
            document.getElementById("text_field").value = "";
            document.getElementById('comment_button').disabled = true;
        },

        /**
         * Оформляем комментарий как ответ
         */

        answer: function(comment_id, root_id, author) {
            var form = document.getElementById('comment_form');

            document.getElementById('parent_id').value          = comment_id;
            document.getElementById('root_id').value            = root_id;
            document.getElementById('comment_button').value     = 'Ответить';
            document.getElementById('comment_answer').innerHTML = 'пользователю ' + '<b>' + author + '</b>';
            document.getElementById('cancel_answer').innerHTML  = '<i class="icon-cancel"></i>';
            document.getElementById('text_field').rows          = 4;
            form.classList.add('answer_form');
            document.getElementById('comment_' + comment_id).appendChild(form);
            document.getElementById('text_field').focus();
        },

        /**
         * Возвращаем исходный стиль комментария
         */

        close_answer: function() {
            var form = document.getElementById('comment_form');

            document.getElementById('parent_id').value          = 0;
            document.getElementById('root_id').value            = 0;
            document.getElementById('comment_button').value     = 'Оставить комментарий';
            document.getElementById('comment_answer').innerHTML = '';
            document.getElementById('cancel_answer').innerHTML  = '';
            document.getElementById('text_field').rows          = 6;
            document.getElementById('page_comments').appendChild(form);
            form.classList.remove('answer_form');
        },

        /**
         * Отключаем кнопку submit, если поле пустое или поле только с пробелами
         */

        enable_button: function() {
            var field_value = document.getElementById("text_field").value.trim();
            if (field_value) {
                document.getElementById('comment_button').disabled = false;
            } else {
                document.getElementById('comment_button').disabled = true;
            }
        }
    }
}());
document.addEventListener("DOMContentLoaded", comments.clear_textarea);