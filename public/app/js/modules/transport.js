/**
* File transport module
*/

var transport = {

    // transport.input = null;
    //
    // /**
    //  * @property {Object} arguments - keep plugin settings and defined callbacks
    //  */
    // transport.arguments = null;
    //
    // /**
    //  * [new]
    //  * create input elem
    //  */
    // transport.prepare = function () {
    //
    //     var input = document.createElement('INPUT');
    //
    //     input.type = 'file';
    //     input.addEventListener('change', editor.transport.fileSelected);
    //
    //     editor.transport.input = input;
    //
    // };
    //
    // /** Clear input when files is uploaded */
    // transport.clearInput = function () {
    //
    //     /** Remove old input */
    //     this.input = null;
    //
    //     /** Prepare new one */
    //     this.prepare();
    //
    // };
    //
    // /**
    //  * Callback for file selection
    //  * @param {Event} event
    //  */
    // transport.fileSelected = function () {
    //
    //     var input       = this,
    //         files       = input.files,
    //         formdData   = new FormData();
    //
    //     formdData.append('files', files[0], files[0].name);
    //
    //     editor.transport.ajax({
    //         data : formdData,
    //         beforeSend : editor.transport.arguments.beforeSend,
    //         success    : editor.transport.arguments.success,
    //         error      : editor.transport.arguments.error
    //     });
    //
    // };
    //
    // /**
    //  * Use plugin callbacks
    //  * @protected
    //  */
    // transport.selectAndUpload = function (args) {
    //
    //     this.arguments = args;
    //     this.input.click();
    //
    // };
    //
    // /**
    //  * Ajax requests module
    //  * @todo use core.ajax
    //  */
    // transport.ajax = function (params) {
    //
    //     var xhr = new XMLHttpRequest(),
    //         beforeSend = typeof params.beforeSend == 'function' ? params.beforeSend : function () {},
    //         success    = typeof params.success    == 'function' ? params.success : function () {},
    //         error      = typeof params.error      == 'function' ? params.error   : function () {};
    //
    //     beforeSend();
    //
    //     xhr.open('POST', editor.settings.uploadImagesUrl, true);
    //
    //     xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    //
    //     xhr.onload = function () {
    //
    //         if (xhr.status === 200) {
    //
    //             success(xhr.responseText);
    //
    //         } else {
    //
    //             editor.core.log('request error: %o', xhr);
    //             error();
    //
    //         }
    //
    //     };
    //
    //     xhr.send(params.data);
    //     this.clearInput();
    //
    // };

    form : null,
    input : null,

    /**
    * @uses for store inputed filename to this.files
    */
    keydownFinishedTimeout : null,

    /**
    * Current attaches will be stored in this object
    * @see this.storeFile
    */
    files : {},

    /**
    * Input where current transport type stored
    */
    transportTypeInput: null,

    init : function () {

        // this.form  = document.getElementById('transportForm');
        // this.input = document.getElementById('transportInput');

        this.createUtils();

        if (!this.form || !this.input) {

            return false;

        }

        this.input.addEventListener('change', this.fileSelected);

    },

    createUtils : function () {

        return

    },

    /**
    * Chose-file button click handler
    */
    selectFile : function (event, type) {

        this.prepareForm({
            type : type
        });
        this.input.click();

    },

    fileSelected : function () {

        codex.transport.form.submit();
        codex.transport.clear();

    },

    prepareForm : function (params) {

        if (!this.transportTypeInput) {

            this.transportTypeInput      = document.createElement('input');
            this.transportTypeInput.type = 'hidden';
            this.transportTypeInput.name = 'type';
            this.form.appendChild(this.transportTypeInput);

        }

        this.transportTypeInput.value = params.type;

    },

    clear : function () {

        this.type = null;
        this.input.value = null;

    },

    response : function (response) {

        if (response.success && response.title) {

            this.storeFile(response);
            // this.appendFileToInput(response.filename);

        } else {

            codex.core.showException(response.message);

        }

    },

    /**
    * Store file in memory
    * Attaches list will be sent form submitting
    */
    storeFile : function (file) {

        if (!file || !file.id) {

            return;

        }

        this.files[file.id] = {
            'title' : file.title,
            'id'    : file.id
        };

        this.appendFileRow(file);

    },

    /**
    * Appends saved file to form
    * Allow edit name by contenteditable element
    */
    appendFileRow : function (file) {

        var attachesZone = document.getElementById('formAttaches'),
            row          = document.createElement('div'),
            filename     = document.createElement('span'),
            deleteButton = document.createElement('span');

        row.classList.add('item');

        switch (file.type) {
            case '1': filename.classList.add('item_file'); break;
            case '2': filename.classList.add('item_image'); break;
            default: break;
        }

        filename.textContent = file.title;
        filename.setAttribute('contentEditable', true);

        deleteButton.classList.add('fl_r', 'button-delete', 'icon-trash');
        deleteButton.addEventListener('click', function () {

            if (this.parentNode.dataset.readyForDelete) {

                delete codex.transport.files[file.id];
                this.parentNode.remove();

            }

            this.parentNode.dataset.readyForDelete = true;
            this.classList.add('button-delete__ready-to-delete');
            this.innerHTML = 'Удалить документ';

        }, false);

        row.appendChild(filename);
        row.appendChild(deleteButton);

        attachesZone.appendChild(row);

        /** Save ID to determine which filename edited */
        row.dataset.id = file.id;
        row.addEventListener('input', this.storeFileName, false);

    },

    /**
    * Saves filename from input to this.files object
    */
    storeFileName : function () {

        /**
        * Clear previous keydown-timeout
        */
        if (codex.transport.keydownFinishedTimeout) {

            window.clearTimeout(codex.transport.keydownFinishedTimeout);

        }

        var input = this;

        /**
        * Start waiting to input finished, then save value to this.files
        */
        codex.transport.keydownFinishedTimeout = window.setTimeout(function () {

            var id    = input.dataset.id,
                title = input.textContent.trim();

            if (title) {

                codex.transport.files[id].title = title;

            }

        }, 300);


    },


    /**
    * Prepares and submit form
    * Send attaches by json-encoded stirng with hidden input
    */
    submitAtlasForm : function () {

        var atlasForm = document.forms.atlas;

        if (!atlasForm) return;

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

            JSONinput.innerHTML = JSON.stringify(codex.editor.state.jsonOutput);

            /**
             * Send form
             */
            atlasForm.submit();

        }, 100);

    }

};

module.exports = transport;
