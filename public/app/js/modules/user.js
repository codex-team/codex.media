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

                codex.alerts.show(response.message || 'File uploading error :(');
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
     * Changes user status
     * @param  {} argument [description]
     * @return {[type]}          [description]
     */
    var changeStatus = function () {

        console.log('user status changing');

    };

    var changePassword = function () {

        var currentPassword = null,
            message         = null,
            csrf            = null;

        var showForm = function (_csrf) {

            var wrapper     = this,
                label       = document.createElement('LABEL'),
                input       = document.createElement('INPUT'),
                button      = document.createElement('SPAN');

            message     = document.createElement('DIV');

            csrf = _csrf;

            label.classList.add('form__label');
            label.textContent = 'Текущий пароль';

            input.classList.add('form__input');
            input.type = 'password';
            currentPassword = input;

            button.classList.add('button');
            button.classList.add('form__hint');
            button.classList.add('master');
            button.textContent = 'Подтвердить';

            button.addEventListener('click', requestChange);

            wrapper.classList.remove('island--centered');
            wrapper.classList.remove('profile-settings__change-password-btn');
            wrapper.innerHTML   = '';
            wrapper.onclick     = '';

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            wrapper.appendChild(button);
            wrapper.appendChild(message);

        };

        var requestChange = function () {

            codex.ajax.call({
                url: '/user/passchange',
                type: 'POST',
                data: JSON.stringify({
                    csrf: csrf,
                    currentPassword: currentPassword.value
                }),
                success: ajaxResponse,
                error: ajaxResponse
            });

        };

        var ajaxResponse = function (response) {

            try {

                response = JSON.parse(response);

            } catch (e) {

                response = {success: 0, message: 'Произошла ошибка'};

            }

            if (!response.success) {

                currentPassword.classList.add('form__input--invalid');

            } else {

                currentPassword.classList.remove('form__input--invalid');

            }

            message.textContent = response.message;

        };

        return {
            showForm: showForm
        };

    }();

    var init = function () {

        // bindEvents();

    };

    // var bindEvents = function () {
    //
    //     var repeatConfirmEmailBtn = document.getElementById('repeat-email-confirmation');
    //
    //     repeatConfirmEmailBtn.addEventListener('click', sendEmeailConfirmation);
    //
    // };

    // var sendEmeailConfirmation = function (e) {
    //
    //     var success = function (response) {
    //
    //         response = JSON.parse(response);
    //
    //         codex.alerts.show(response.message);
    //         e.target.classList.remove('loading');
    //
    //     };
    //
    //     e.target.classList.add('loading');
    //
    //     codex.ajax.call({
    //         url: '/ajax/confirmation-email',
    //         success: success
    //     });
    //
    // };

    return {
        changePassword: changePassword,
        changeStatus: changeStatus,
        photo: photo
    };

}();
