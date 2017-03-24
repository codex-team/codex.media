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
         * Changes user's photo
         * @param  {Event}  event   click event
         */
        var change = function ( event , transportType ) {

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
        var error = function (error) {
            console.log(error);
        };

        /**
         * Photo uploading callback
         * @param  {String} response    server answer
         */
        var uploaded = function (response) {

            response = JSON.parse(response);

            if ( !response.success ) {

                codex.alert.show(response.message || 'File uploading error :(');
                return;

            }

            console.log("response: %o", response);
        };

        return {
            change : change
        };

    }();

    var init = function () {

        // bindEvents();

    };

    var bindEvents = function () {

        var repeatConfirmEmailBtn = document.getElementById('repeat-email-confirmation');

        repeatConfirmEmailBtn.addEventListener('click', sendEmeailConfirmation);

    };

    var sendEmeailConfirmation = function (e) {

        var success = function (response) {

            response = JSON.parse(response);

            codex.alerts.show(response.message);
            e.target.classList.remove('loading');

        };

        e.target.classList.add('loading');

        codex.ajax.call({
            url: '/ajax/confirmation-email',
            success: success
        });

    };

    return {
        init: init,
        photo: photo
    };

}();
