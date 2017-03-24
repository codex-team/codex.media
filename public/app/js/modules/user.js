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

                codex.alert.show(response.message || 'File uploading error :(');
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
        init: init,
        changeStatus: changeStatus,
        photo: photo
    };

}();
