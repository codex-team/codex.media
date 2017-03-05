module.exports = (function () {

    var commentsList = null,
        anchor       = document.location.hash;

    function init() {

        commentsList = document.getElementById('commentsList');

        if (anchor) {

            highligthAnchor();

        }

    }

    function appendForm(elem) {

        var holder = elem.target,
            holderParent = holder.parentNode,
            form  = createForm(holder);

        holderParent.removeChild(holder);
        holderParent.appendChild(form);

        returnTextareaByForm(form).focus();

    }

    function createForm(holder) {

        var holderParent = holder.parentNode,
            textarea     = createTextarea(holder),
            button       = createButton(),
            form         = document.createElement('DIV');

        form.classList.add('comments-form');
        form.dataset.parentid          = holderParent.dataset.parentid;
        form.dataset.rootid            = holderParent.dataset.rootid;
        form.dataset.sendcommentaction = holderParent.dataset.sendcommentaction;

        form.appendChild(textarea);
        form.appendChild(button);

        return form;

    }

    function createTextarea(holder) {

        var textarea = document.createElement('TEXTAREA');

        textarea.classList.add('comment-form__text');
        textarea.placeholder = holder.innerHTML;
        textarea.rows = 1;
        textarea.required = true;
        textarea.addEventListener('keydown', keydownSubmitHandler, false);
        textarea.addEventListener('blur', blurTextareaHandler, false);

        return textarea;

    }

    function createButton() {

        var button = document.createElement('DIV');

        button.classList.add('comment-form__button', 'button');
        button.innerHTML = 'Оставить комментарий';
        button.addEventListener('click', sendFormByAjax, false);

        return button;

    }

    function returnTextareaByForm(form) {

        return form.getElementsByTagName('TEXTAREA')[0];

    }

    /** Show holder for comment-form */
    function createHolder() {

        var holder = document.createElement('DIV');

        holder.classList.add('comment-form__placeholder');
        holder.addEventListener('click', appendForm, false);
        holder.innerHTML = 'Ваш комментарий...';

        return holder;

    }

    /** Remove form on textarea blur */
    function blurTextareaHandler(event) {

        var textarea = event.target,
            form     = textarea.parentNode,
            parentId = form.dataset.parentid;

        if (!textarea.value) {

            removeForm(parentId);

        }

    }

    function removeForm(commentId) {

        var formWrapper = document.getElementById('replyFormToComment' + commentId),
            form   = formWrapper.getElementsByClassName('comments-form')[0],
            holder = createHolder();

        form.remove();
        formWrapper.appendChild(holder);

    }

    /** Highligth comment by id for a time */
    function highligthComment(commentId) {

        var commentId = 'comment_' + commentId,
            comment = document.getElementById(commentId);

        console.log(commentId, comment);

        comment.classList.add('comment--highlited');

        window.setTimeout(function () {

            comment.classList.remove('comment--highlited');

        }, 1000);

    }

    /*
     * Если нажаты сочетания Ctrl+Enter или Cmd+Enter, отправляем комментарий
     */
    function keydownSubmitHandler(event) {

        var CtrlPressed  = event.ctrlKey || event.metaKey,
            EnterPressed = event.keyCode == 13;

        if ( CtrlPressed && EnterPressed ) {

            sendFormByAjax(event);

        }

    }

    function sendFormByAjax(event) {

        var form      = event.target.parentNode,
            text      = returnTextareaByForm(form).value,
            formData  = new FormData(),
            rootId    = form.dataset.rootid,
            parentId  = form.dataset.parentid,
            actionURL = form.dataset.sendcommentaction;

        formData.append('root_id', rootId);
        formData.append('parent_id', parentId);
        formData.append('comment_text', text);
        formData.append('csrf', window.csrf);

        codex.ajax.call({
            type: 'POST',
            url: actionURL,
            data: formData,
            beforeSend : function () {},
            success : function (response) {

                console.log(actionURL);

                response = JSON.parse(response);

                if (response.success) {

                    // если на странице не было ни одного комментария, то удаляем мотиватор
                    if (commentsList.dataset.count == 0) {

                        commentsList.innerHTML = '';

                    }

                    commentsList.innerHTML += response.comment;
                    commentsList.dataset.count++;

                    window.scrollTo(0, document.body.scrollHeight);

                    highligthComment(response.commentId);

                    removeForm(parentId);

                } else {

                    codex.alerts.show(response.error);

                }

            }

        });

    }

    /** Highligth comment if anchor */
    function highligthAnchor() {

        var commentId = anchor.slice(anchor.lastIndexOf('_') + 1);

        highligthComment(commentId);

    };

    return {
        init : init,
        appendForm : appendForm
    };

}());
