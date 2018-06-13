/**
 * Comments module
 * @author  @guryn @neSpecc
 * @copyright CodeX Team https://github.com/codex-team
 * @version 1.1.0
 */
module.exports = (function () {

    var commentsList = null,
        anchor       = document.location.hash;

    var CSS_ = {
        replyForm :         'comments-form',
        replyTextarea :     'comment-form__text',
        replyOpened :       'comment-form__placeholder--opened',
        replySubmitButton : 'comment-form__button',
        highlighted :       'comment--highligthed'
    };

    /**
     * Settings-menu toggler selector
     * @type {String}
     */
    var menuTogglerSelector = '.js-comment-settings';

    /**
     * Initialize comments
     * @param {Object} data params
     * @param {Object} list — list of comments,
     *                        passed from moduleDispatcher
     */
    function init(data, list) {

        commentsList = list;

        if (anchor) {

            highligthAnchor();

        }

    }

    /**
     * Remove holder and append form for comment
     * @param {Element} placeholder 'Write reply...' button
     */
    function reply( replyButton ) {

        /** If reply already opened, do noting */
        if ( replyButton.classList.contains( CSS_.replyOpened ) ) {

            return;

        }

        /** Get reply params from dataset */
        var replyParams = {
            parentId : replyButton.dataset.parentId,
            rootId   : replyButton.dataset.rootId,
            action   : replyButton.dataset.action
        };

        /** Create reply form */
        var form = createForm( replyParams );

        /** Insert form after reply button */
        codex.core.insertAfter( replyButton, form );

        replyButton.classList.add( CSS_.replyOpened );
        getFormTextarea(form).focus();

    }

    /**
     * Returns reply form
     *
     * @param {object} params
     * @param {Number} params.parentId     parent comment's id
     * @param {Number} params.rootId       root comment's id
     * @param {String} params.action       URL for saving
     *
     * @return {Element} element that holds textarea and submit-button
     */
    function createForm( params ) {

        var textarea     = createTextarea(),
            button       = createButton(),
            form         = document.createElement('DIV');

        form.classList.add(CSS_.replyForm);

        /** Store data in Textarea */
        textarea.dataset.parentId = params.parentId;
        textarea.dataset.rootId   = params.rootId;
        textarea.dataset.action   = params.action;

        form.appendChild(textarea);
        form.appendChild(button);

        return form;

    }

    /** Return textarea for form for comment */
    function createTextarea() {

        var textarea = document.createElement('TEXTAREA');

        textarea.classList.add(CSS_.replyTextarea);
        textarea.placeholder = 'Ваш комментарий';

        textarea.addEventListener('keydown', keydownSubmitHandler, false);
        textarea.addEventListener('blur', blurTextareaHandler, false);

        codex.autoresizeTextarea.addListener(textarea);

        return textarea;

    }

    /** Return submit button for form*/
    function createButton() {

        var button = document.createElement('DIV');

        button.classList.add( CSS_.replySubmitButton, 'button', 'master');
        button.textContent = 'Отправить';

        button.addEventListener('click', submitClicked_, false);

        return button;

    }

    /**
     * Reply submit button click handler
     */
    function submitClicked_() {

        var submit = this,
            form   = submit.parentNode,
            textarea = getFormTextarea(form);

        send_( textarea );

    }

    /* Return textarea for given form */
    function getFormTextarea(form) {

        return form.getElementsByTagName('TEXTAREA')[0];

    }

    /**
     * Remove form on textarea blur
     * @param {Event} blur Event
     */
    function blurTextareaHandler( event ) {

        var textarea  = event.target,
            form      = textarea.parentNode,
            commentId = textarea.dataset.parentId;

        if (!textarea.value.trim()) {

            removeForm(form, commentId);

        }

    }

    /**
     * Removes reply form
     * @param {Element} form
     * @param {Number} commentId   reply target comment id
     */
    function removeForm( form, commentId ) {

        var replyButton = document.getElementById('reply' + commentId );

        form.remove();
        replyButton.classList.remove(CSS_.replyOpened);

    }

    /**
     * Catch Ctrl+Enter or Cmd+Enter for send form
     * @param {Event} event    Keydown Event
     */
    function keydownSubmitHandler(event) {

        var ctrlPressed  = event.ctrlKey || event.metaKey,
            enterPressed = event.keyCode == 13,
            textarea = event.target;

        if ( ctrlPressed && enterPressed ) {

            send_( textarea );

            event.preventDefault();

        }

    }

    /**
     * Ajax function for submit comment
     * @param {Element} textarea    input with dataset and text
     */
    function send_( textarea ) {

        var formData  = new FormData(),
            form      = textarea.parentNode,
            submitBtn = form.querySelector('.' + CSS_.replySubmitButton),
            rootId    = textarea.dataset.rootId,
            parentId  = textarea.dataset.parentId,
            actionURL = textarea.dataset.action;

        formData.append('root_id', rootId);
        formData.append('parent_id', parentId);
        formData.append('comment_text', textarea.value);
        formData.append('csrf', window.csrf);

        codex.ajax.call({
            type: 'POST',
            url: actionURL,
            data: formData,
            beforeSend : function () {

                submitBtn.classList.add('loading');

            },
            success : function (response) {

                var comment;

                submitBtn.classList.remove('loading');

                response = JSON.parse(response);

                if (!response.success) {

                    codex.alerts.show({
                        type: 'error',
                        message: response.error
                    });
                    return;

                }

                /** Remove form and return placeholder */
                removeForm(form, parentId);

                /** Remove empty-feed block */
                removeEmptyCommentsBlock();

                comment = codex.core.parseHTML(response.comment)[0];
                commentsList.appendChild(comment);

                /** Scroll down to the new comment */
                window.scrollTo(0, document.body.scrollHeight);

                /** Highligth new comment */
                highligthComment(response.commentId);

                /** If menu found, activate it */
                activateMenu(comment);

            }

        });

    }

    /**
     * Removes empty-feed motivation
     */
    function removeEmptyCommentsBlock() {

        var emptyCommentsBlock = document.querySelector('.js-empty-comments');

        if (!emptyCommentsBlock) {

            return;

        }

        emptyCommentsBlock.remove();

    }

    /**
     * If menu-toggler found in comment
     * @return {Element} comment - comment's island
     */
    function activateMenu( comment ) {

        var  menuToggler = comment.querySelector(menuTogglerSelector);

        if (!menuToggler) {

            return;

        }

        codex.islandSettings.prepareToggler(menuToggler, menuTogglerSelector);

    }

    /**
     * Highligth comment by id for a time
     * @param {Number} commentId   id comment to highlight
     */
    function highligthComment(commentId) {

        var comment = document.getElementById('comment' + commentId);

        comment.classList.add(CSS_.highlighted);

        window.setTimeout(function () {

            comment.classList.remove(CSS_.highlighted);

        }, 500);

    }

    /** Highligth comment if anchor is in url */
    function highligthAnchor() {

        var numbers = anchor.match(/\d+/),
            commentId;

        if (!numbers) return;

        commentId = numbers[0];

        highligthComment(commentId);

    }

    /**
     * Comment removing action
     * @return {Event} click Event
     */
    function remove() {

        var itemClicked = this,
            targetId    = itemClicked.dataset.id;

        if (!window.confirm('Подтвердите удаление комментария')) {

            return;

        }

        document.location = '/delete-comment/' + targetId;

    }

    return {
        init : init,
        reply : reply,
        remove : remove
    };

}());
