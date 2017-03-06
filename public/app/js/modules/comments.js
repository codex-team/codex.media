module.exports = (function () {

    var commentsList = null,
        anchor       = document.location.hash;

    function init(data) {

        commentsList = document.getElementById(data.listID);

        if (anchor) {

            highligthAnchor();

        }

    }

    /** Remove holder and append form for comment */
    function appendForm(placeholder) {

        var holder       = placeholder.target,
            holderParent = holder.parentNode,
            form         = createForm(holder);

        holderParent.removeChild(holder);
        holderParent.appendChild(form);

        getFormTextarea(form).focus();

    }

    /** Return form for comment */
    function createForm(holder) {

        var holderParent = holder.parentNode,
            textarea     = createTextarea(holder),
            button       = createButton(),
            form         = document.createElement('DIV');

        form.classList.add('comments-form');
        form.dataset.parentId = holderParent.dataset.parentId;
        form.dataset.rootId   = holderParent.dataset.rootId;
        form.dataset.action   = holderParent.dataset.action;

        form.appendChild(textarea);
        form.appendChild(button);

        return form;

    }

    /** Return textarea for form for comment */
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

    /** Return submit button for form*/
    function createButton() {

        var button = document.createElement('DIV');

        button.classList.add('comment-form__button', 'button');
        button.innerHTML = 'Оставить комментарий';
        button.addEventListener('click', sendFormByAjax, false);

        return button;

    }

    /* Return textarea for given form */
    function getFormTextarea(form) {

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
            parentId = form.dataset.parentId;

        if (!textarea.value) {

            removeForm(parentId);

        }

    }

    /** Remove form by commentId and put back placeholder */
    function removeForm(commentId) {

        var formWrapper = document.getElementById('replyFormToComment' + commentId),
            form        = formWrapper.getElementsByClassName('comments-form')[0],
            holder      = createHolder();

        form.remove();
        formWrapper.appendChild(holder);

    }

    /** Highligth comment by id for a time */
    function highligthComment(commentId) {

        var commentId = 'comment_' + commentId,
            comment = document.getElementById(commentId);

        comment.classList.add('comment--highligthed');

        window.setTimeout(function () {

            comment.classList.add('comment--highligthed-transition');
            comment.classList.remove('comment--highligthed');

            window.setTimeout(function () {

                comment.classList.remove('comment--highligthed-transition');

            }, 1000);

        }, 1000);

    }

    /** Catch Ctrl+Enter or Cmd+Enter for send form */
    function keydownSubmitHandler(event) {

        var ctrlPressed  = event.ctrlKey || event.metaKey,
            enterPressed = event.keyCode == 13;

        if ( ctrlPressed && enterPressed ) {

            sendFormByAjax(event);

        }

    }

    /** Ajax function for submit comment */
    function sendFormByAjax(event) {

        var form      = event.target.parentNode,
            text      = getFormTextarea(form).value,
            formData  = new FormData(),
            rootId    = form.dataset.rootId,
            parentId  = form.dataset.parentId,
            actionURL = form.dataset.action;

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

                    // if no comments are in comments list, then remove motivator
                    if (commentsList.dataset.count == 0) {

                        commentsList.innerHTML = '';

                    }

                    // Append new comment to comments list
                    commentsList.innerHTML += response.comment;
                    commentsList.dataset.count++;

                    // Scroll down to new comment
                    window.scrollTo(0, document.body.scrollHeight);

                    // Highligth new comment
                    highligthComment(response.commentId);

                    // Remove form and return placeholder
                    removeForm(parentId);

                } else {

                    // Show error
                    codex.alerts.show(response.error);

                }

            }

        });

    }

    /** Highligth comment if anchor is in url */
    function highligthAnchor() {

        var commentId = anchor.slice(anchor.lastIndexOf('_') + 1);

        highligthComment(commentId);

    };

    return {
        init : init,
        appendForm : appendForm
    };

}());
