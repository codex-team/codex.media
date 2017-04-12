/**
 * File transport module
 *
 * @module Transport module. Uploads file and returns some response from server
 * @copyright Codex-Team 2017
 *
 * @example
 *
 * Basic usage :
 *  codex.transport.init( {
 *      url : fetchURL,
 *      multiple : bool,
 *      accept : string  // http://htmlbook.ru/html/input/accept
 *      beforeSend : Function,
 *      success : Function,
 *      error : Function
 *      data : Object — additional data
 * });
 *
 * You can handle all of this event like:
 *  - what should happen before data sending with XMLHTTP
 *  - what should after success request
 *  - error handler
 */

module.exports = ( function (transport) {

    /** Empty configuration */
    var config_ = null;

    /** File holder */
    transport.input = null;

    /** initialize module */
    transport.init = function (configuration) {

        if (!configuration.url) {

            codex.core.log('can\'t send request because `url` is missed', 'Transport module', 'error');
            return;

        }

        config_ = configuration;

        var inputElement = document.createElement('INPUT');

        inputElement.type = 'file';

        if (config_ && config_.multiple) {

            inputElement.setAttribute('multiple', 'multiple');

        }

        if (config_ && config_.accept) {

            inputElement.setAttribute('accept', config_.accept);

        }

        inputElement.addEventListener('change', send_, false);

        /** Save input */
        transport.input = inputElement;

        /** click input to show upload window */
        clickInput_();

    };

    var clickInput_ = function () {

        transport.input.click();

    };

    /**
     * Sends transport AJAX request
     */
    var send_ = function () {

        var url        = config_.url,
            beforeSend = config_.beforeSend,
            success    = config_.success,
            error      = config_.error,
            formData   = new FormData(),
            files      = transport.input.files;

        if (files.length > 1) {

            for (var i = 0; i < files.length; i++) {

                formData.append('files[]', files[i], files[i].name);

            }

        } else {

            formData.append('files', files[0], files[0].name);

        }

        /**
         * Append additional data
         */
        if ( config_.data !== null && typeof config_.data === 'object' ) {

            for (var key in config_.data) {

                formData.append(key, config_.data[key]);

            }

        }

        codex.ajax.call({
            type : 'POST',
            data : formData,
            url : url,
            beforeSend : beforeSend,
            success : success,
            error : error
        });

    };

    return transport;

})({});
