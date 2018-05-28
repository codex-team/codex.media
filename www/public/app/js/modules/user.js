/**
 * User methods module
 */
module.exports = function () {

    /**
     * Manupulations with user photo
     * @return {Object} - Module
     */
    var photo = function () {

        /**
         * Mark elements with this name="" to dynamically update their sources
         * @type {String}
         */
        var updatableElementsName = 'js-img-updatable';

        /**
         * Changes user's photo
         * @param  {Event}  event   click event
         */
        var change = function ( event, transportType ) {

            codex.transport.init({
                url : '/upload/' + transportType,
                success : uploaded,
                error   : error
            });

        };

        /**
         * Uploading error
         * @param  {Object} error
         */
        var error = function (uploadError) {

            console.log(uploadError);

        };

        /**
         * Photo uploading callback
         * @param  {String} response    server answer
         */
        var uploaded = function (response) {

            response = JSON.parse(response);

            if ( !response.success ) {

                codex.alerts.show({
                    type: 'error',
                    message: response.message || 'File uploading error :('
                });

                return;

            }

            console.assert( response.data && response.data.url, 'Wrong response data');

            updateAll( response.data.url );

        };

        /**
         * Updates all user photo sources
         * @uses   updatableElementsName  to find img tags
         * @param  {String} newSource
         */
        var updateAll = function ( newSource) {

            var updatebleImages = document.getElementsByName(updatableElementsName);

            for (var i = updatebleImages.length - 1; i >= 0; i--) {

                updatebleImages[i].src = newSource;

            }

        };

        return {
            change : change
        };

    }();

    /**
     * Updatin user ROLE or STATUS
     * @type {{status, role}}
     */
    var promote = function () {

        var status = function (args) {

            var itemClicked = this,
                userId = itemClicked.dataset.id,
                value = args.value;

            sendRequest(itemClicked, 'status', userId, value);

        };

        var role = function (args) {

            var itemClicked = this,
                userId = itemClicked.dataset.id,
                value = args.value;

            sendRequest(itemClicked, 'role', userId, value);

        };

        /**
         * Change user role or status request
         * @param {Element} itemClicked     - menu item element
         * @param {string}  field           - field to save (role|status)
         * @param {Number}  userId          - target user id
         * @param {Number}  value           - new value
         */
        var sendRequest = function (itemClicked, field, userId, value) {

            var url = '/user/' + userId + '/change/' + field,
                requestData = new FormData();

            requestData.append('value', value);

            codex.ajax.call({
                url : url,
                type : 'POST',
                data: requestData,
                beforeSend : function () {

                    itemClicked.classList.add('loading');

                },
                success: function (response) {

                    var menuIndex = itemClicked.dataset.index,
                        itemIndex = itemClicked.dataset.itemIndex;

                    response = JSON.parse(response);

                    itemClicked.classList.remove('loading');

                    codex.islandSettings.updateItem(menuIndex, itemIndex, response.buttonText, null, {
                        value: response.buttonValue
                    });

                    codex.alerts.show({
                        type: response.success ? 'success' : 'error',
                        message: response.message || 'Не удалось сохранить изменения'
                    });

                }
            });

        };

        return {
            status : status,
            role   : role
        };

    }();


    var changePassword = function () {

        var form    = null,
            input   = null,
            button  = null;
        /**
         * Shows form with input for current password
         *
         * @param lockButton
         */
        var showForm = function (lockButton) {

            lockButton.classList.add('hide');

            form = document.getElementById('change-password-form');
            input = document.getElementById('change-password-input');

            form.classList.remove('hide');


        };

        /**
         * Handler for set password button
         *
         * @param form_
         */
        var set = function (form_) {

            form = form_;
            requestChange(form, true);
            showSuccessMessage();

        };

        /**
         * Requests email with change password link
         *
         * @param button_
         * @param dontShowResponse - if TRUE, response will be ignored
         */
        var requestChange = function (button_, dontShowResponse) {

            button = button_;
            button.classList.add('loading');

            var data = new FormData();

            data.append('csrf', window.csrf);
            data.append('currentPassword', input ? input.value : '');

            codex.ajax.call({
                url: '/user/passchange',
                type: 'POST',
                data: data,
                success: dontShowResponse ? null : ajaxResponse,
                error: ajaxResponse
            });

        };

        /**
         * Repeat password change email sending
         *
         * @param button_
         */
        var repeatEmail = function (button_) {

            button_.classList.add('loading');

            var data = new FormData();

            data.append('csrf', window.csrf);
            data.append('repeatEmail', true);

            codex.ajax.call({
                url: '/user/passchange',
                type: 'POST',
                data: data,
                success: function () {

                    button_.classList.remove('loading');

                    codex.alerts.show({
                        type: 'success',
                        message: 'Мы отправили на вашу почту письмо'
                    });

                },
                error: function () {

                    button_.classList.remove('loading');

                    codex.alerts.show({
                        type: 'error',
                        message: 'Произошла ошибка'
                    });

                }
            });

        };

        var ajaxResponse = function (response) {

            button.classList.remove('loading');

            try {

                response = JSON.parse(response);

            } catch (e) {

                response = {success: 0, message: 'Произошла ошибка'};

            }

            if (!response.success) {

                if (input) input.classList.add('form__input--invalid');

                codex.alerts.show({
                    type: 'error',
                    message:response.message
                });


            } else {

                showSuccessMessage();
                return;

            }


        };

        /**
         * Shows success email sending message
         *
         */
        var showSuccessMessage = function () {

            codex.alerts.show({
                type: 'success',
                message: 'Мы выслали инструкцию на вашу почту'
            });

            form.classList.add('hide');

            form = document.getElementById('change-password-success');
            form.classList.remove('hide');

        };

        return {
            showForm: showForm,
            requestChange: requestChange,
            set: set,
            repeatEmail: repeatEmail,
        };

    }();

    /**
     * Working with bio
     */
    var bio = function () {

        /**
         * Edited textarea cache
         * @type {Element|null}
         */
        var textarea = null;

        /**
         * Edit bio click handler;
         * @param {Element} button  - button clicked
         */
        var edit = function ( button ) {

            var opened = button.querySelector('textarea');

            if (opened) {

                return;

            }

            textarea = document.createElement('TEXTAREA');
            textarea.innerHTML = button.textContent.trim();
            textarea.addEventListener('keydown', keydown);

            button.innerHTML = '';
            button.appendChild(textarea);

            textarea.focus();

            /** Fire autoresize */
            codex.autoresizeTextarea.addListener(textarea);

        };

        /**
         * Bio textarea keydowns
         * Sends via AJAX by ENTER
         */
        var keydown = function ( event ) {

            if ( event.keyCode == codex.core.keys.ENTER ) {

                send(this.value);
                event.preventDefault();

            }

        };

        /**
         * Sends bio field
         * @param  {String} val textarea value
         */
        var send = function (val) {

            if (!val.trim()) {

                codex.alerts.show({
                    type: 'error',
                    message: 'Write something about yourself'
                });
                return;

            }

            var formData = new FormData();

            formData.append('bio', val);
            formData.append('csrf', window.csrf);

            codex.ajax.call({
                type : 'POST',
                url : '/user/updateBio',
                data : formData,
                beforeSend: beforeSend,
                success : saved
            });

        };

        /**
         * Simple beforeSend method
         */
        var beforeSend = function () {

            textarea.classList.add('loading');

        };

        /**
         * Success saving callback
         */
        var saved = function (response) {

            response = JSON.parse(response);

            if (!response.success || !response.bio) {

                textarea.classList.remove('loading');
                codex.alerts.show({
                    type: 'error',
                    message: 'Saving error, sorry'
                });
                return;

            }

            var newBio = document.createTextNode(response.bio || '');

            /** Update user's CSRF token */
            window.csrf = response.csrf;

            codex.core.replace(textarea, newBio);

        };

        return {
            edit: edit
        };

    }();

    var email = function () {

        var currentEmail    = null,
            loadingButton   = null;

        var saved = function (response) {

            try {

                response = JSON.parse(response);

                if (response.success) {

                    codex.core.replace(currentEmail.parentNode, codex.core.parseHTML(response.island)[0]);

                    codex.alerts.show({
                        type: 'success',
                        message: 'Адрес почты обновлен. Теперь вам нужно подтвердить его, перейдя по ссылке в письме.'
                    });

                    currentEmail = null;
                    return;

                }

            } catch (e) {}

            loadingButton.classList.remove('loading');

            codex.alerts.show({
                type: 'error',
                message: response.message || 'Произошла ошибка, попробуйте позже'
            });

        };

        var send = function () {

            if (currentEmail.value.trim() == '') {

                codex.alerts.show({
                    type: 'error',
                    message: 'Введите email'
                });

                return;

            }

            loadingButton = this;
            loadingButton.classList.add('loading');

            var data = new FormData();

            data.append('email', currentEmail.value);
            data.append('csrf', window.csrf);

            codex.ajax.call({
                url: 'user/changeEmail',
                type: 'POST',
                data: data,
                success: saved,
                error: saved
            });

        };

        var sendConfirmation = function (button) {

            var success = function (response) {

                response = JSON.parse(response);

                codex.alerts.show({
                    type: 'success',
                    message: response.message
                });
                button.classList.remove('loading');

            };

            button.classList.add('loading');

            codex.ajax.call({
                url: '/ajax/confirmation-email',
                success: success
            });

        };

        var changed = function (input) {

            if (currentEmail) {

                return;

            }

            currentEmail = input;

            var saveButton = document.createElement('BUTTON'),
                sendButton = input.parentNode.querySelector('button');

            if (sendButton) sendButton.classList.remove('master');

            saveButton.classList.add('button', 'master');
            saveButton.textContent = 'Сохранить';

            saveButton.addEventListener('click', send);

            input.oninput = null;
            input.parentNode.appendChild(saveButton);

        };

        var set = function (button) {

            button.classList.add('hide');

            var form = document.getElementById('set-email-form');

            form.classList.remove('hide');

            currentEmail = document.getElementById('set-email-input');

        };

        return {
            sendConfirmation: sendConfirmation,
            changed: changed,
            send: send,
            set: set,
        };

    }();


    return {
        changePassword: changePassword,
        promote: promote,
        photo: photo,
        bio : bio,
        email: email,
    };

}();
