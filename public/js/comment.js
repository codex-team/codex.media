function answer(comment_id, author) {
    var is_answer =  document.getElementById('answer_to_comment').value;
    if (is_answer != comment_id) {
        document.getElementById('answer_to_comment').value=comment_id;
        document.getElementById('comment_button').value='Ответить';
        document.getElementById('comment_answer').innerHTML='пользователю ' + author;
    } else {
        document.getElementById('answer_to_comment').value=0;
        document.getElementById('comment_button').value='Оставить комментарий';
        document.getElementById('comment_answer').innerHTML='';
    }
}