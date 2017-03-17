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
 *      input : {
 *          multiple : bool,
 *          accept : string  // http://htmlbook.ru/html/input/accept
 *      },
 *      callbacks : {
 *          beforeSend : Function,
 *          success : Function,
 *          error : Function
 *      }
 * });
 *
 * You can handle all of this event like:
 *  - what should happen before data sending with XMLHPPT
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

            return;

        }

        config_ = configuration;

        new Promise( function (resolve, reject) {

            try {

                var inputElement = document.createElement('INPUT');

                inputElement.type = 'file';

                if (config_.input && config_.input.multiple) {

                    inputElement.setAttribute('multiple', 'multiple');

                }

                if (config_.input && config_.input.accept) {

                    inputElement.setAttribute('accept', config_.input.accept);

                }

                inputElement.addEventListener('change', sendAjaxRequest_, false);

                /** Save input */
                transport.input = inputElement;

                resolve();

            } catch (err) {

                reject(err);

            }

        })
            /** click input to show upload window */
            .then(clickInput_);

    };

    var clickInput_ = function () {

        transport.input.click();

    };

    var sendAjaxRequest_ = function () {

        var url        = config_.url,
            beforeSend = config_.callbacks.beforeSend,
            success    = config_.callbacks.success,
            error      = config_.callbacks.error,
            formData   = new FormData(),
            files      = transport.input.files,
            i;

        for ( i = 0; i < files.length; i++) {

            formData.append('files[]', files[i], files[i].name);

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
