/*
**  Очищаем textarea после перезагрузки
*/

function clear_textarea() {
    document.getElementById("text_field").value = "";
    document.getElementById('comment_button').disabled = true;
}

document.addEventListener("DOMContentLoaded", clear_textarea);


/*
** Оформляем комментарий как ответ
*/

function answer(comment_id, root_id, author) {
    var is_answer =  document.getElementById('parent_id').value;
    if (is_answer != comment_id) {
        document.getElementById('parent_id').value = comment_id;
        document.getElementById('root_id').value = root_id;
        document.getElementById('comment_button').value = 'Ответить';
        document.getElementById('comment_answer').innerHTML = 'пользователю ' + '<b>' + author + '</b>';
    } else {
        document.getElementById('parent_id').value = 0;
        document.getElementById('root_id').value = 0;
        document.getElementById('comment_button').value = 'Оставить комментарий';
        document.getElementById('comment_answer').innerHTML = '';
    }
}

/*
** Отключаем кнопку submit, если поле пустое
*/

  function enable_button() {
    var field_value = document.getElementById("text_field").value;
    if (field_value.length > 0)
        document.getElementById('comment_button').disabled = false;
    else
        document.getElementById('comment_button').disabled = true;
}
