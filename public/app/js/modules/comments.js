var comments = (function () {

    var commentsList        = null,
        sendCommentsFormURL = '',
        classNameReplyForms = 'comment-form__text';

    function init() {

        // form     = document.getElementById('comment_form');
        // textarea = document.getElementById('add_comment_textarea');
        commentsList = document.getElementById('commentsList');

        setListeners();

    }

    function setListeners() {

        var textareas = document.getElementsByClassName(classNameReplyForms);

        for (var i = 0; i < textareas.length; i++) {

            var textarea = textareas[i];

            // ctrl(cmd) + enter
            textarea.addEventListener('keydown', keydownSubmitHandler_, false);

            // form.addEventListener('submit', sendFormByAjax_, false);

        }

    }

    /*
     * Если нажаты сочетания Ctrl+Enter или Cmd+Enter, отправляем комментарий
     */
    function keydownSubmitHandler_(event) {

        var CtrlPressed  = event.ctrlKey || event.metaKey,
            EnterPressed = event.keyCode == 13;

        if ( CtrlPressed && EnterPressed ) {

            sendFormByAjax_(event);

        }

    }

    function sendFormByAjax_(event) {

        event.preventDefault();

        var rootId   = event.target.dataset.rootid,
            parentId = event.target.dataset.parentid,
            text     = event.target.value,
            formData = new FormData();

        console.log(event.target.dataset);

        sendCommentsFormURL = event.target.dataset.sendcommentaction;

        formData.append('root_id', rootId);
        formData.append('parent_id', parentId);
        formData.append('comment_text', text);
        formData.append('csrf', window.csrf);

        codex.ajax.call({
            type: 'POST',
            url: sendCommentsFormURL,
            data: formData,
            beforeSend : function () {},
            success : function (response) {

                response = JSON.parse(response);

                if (response.success) {

                    if (commentsList.dataset.count == 0) {

                        commentsList.innerHTML = '';

                    }

                    commentsList.innerHTML += response.comment;
                    commentsList.dataset.count++;

                    clearForm(event.target);

                    window.scrollTo(0, document.body.scrollHeight);

                    setListeners();

                } else {

                    codex.alerts.show(response.error);

                }

            }

        });

    }

    function clearForm(textarea) {

        textarea.value = '';

        textarea.blur();

    }

    return {
        init: init
    };

}());

module.exports = comments;
