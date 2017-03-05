module.exports = (function () {

    var commentsList = null;

    function init() {

        commentsList = document.getElementById('commentsList');

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
        button.addEventListener('click', sendFormByAjax_, false);

        return button;

    }

    function returnTextareaByForm(form) {

        return form.getElementsByTagName('TEXTAREA')[0];

    }

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

                response = JSON.parse(response);

                if (response.success) {

                    // если на странице не было ни одного комментария, то удаляем мотиватор
                    if (commentsList.dataset.count == 0) {

                        commentsList.innerHTML = '';

                    }

                    commentsList.innerHTML += response.comment;
                    commentsList.dataset.count++;

                    window.scrollTo(0, document.body.scrollHeight);

                    var comment = document.getElementById('comment_' + response.commentId);

                    comment.classList.remove('comment--highlited');

                    removeForm(form);

                } else {

                    codex.alerts.show(response.error);

                }

            }

        });

    }

    function removeForm(form) {

        var parent = form.parentNode,
            holder = createHolder();

        form.remove();
        parent.appendChild(holder);

    }

    return {
        init : init,
        appendForm : appendForm
    };

}());
