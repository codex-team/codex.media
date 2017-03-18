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
            files      = transport.input.files;

        for ( var i = 0; i < files.length; i++) {

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

    /**
     * Prepares and submit form
     * Send attaches by json-encoded stirng with hidden input
     */
    transport.submitAtlasForm = function () {

        var atlasForm = document.forms.atlas;

        if (!atlasForm) {

            return;

        }

        var attachesInput = document.createElement('input');

        attachesInput.type = 'hidden';
        attachesInput.name = 'attaches';
        attachesInput.value = JSON.stringify(this.files);

        atlasForm.appendChild(attachesInput);

        /** CodeX.Editor */
        var JSONinput = document.getElementById('json_result');

        /**
         * Save blocks
         */
        codex.editor.saver.saveBlocks();

        window.setTimeout(function () {

            var blocksCount = codex.editor.state.jsonOutput.length;

            if (!blocksCount) {

                JSONinput.innerHTML = '';

            } else {

                JSONinput.innerHTML = JSON.stringify({ data: codex.editor.state.jsonOutput} );

            }

            /**
             * Send form
             */
            atlasForm.submit();

        }, 100);

    };

    /**
     * Submits editor form for opening in full-screan page without saving
     */
    transport.openEditorFullscrean = function () {

        var atlasForm = document.forms.atlas,
            openEditorFlagInput = document.createElement('input');

        openEditorFlagInput.type = 'hidden';
        openEditorFlagInput.name = 'openFullScreen';
        openEditorFlagInput.value = 1;

        atlasForm.append(openEditorFlagInput);

        transport.submitAtlasForm();

    };

    return transport;

})({});
