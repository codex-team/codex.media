/**
* File transport module
*/

var transport = {

    transportURL : '/file/transport',

    /**
    * Field for file
    */
    input : null,

    /**
    * Current transport type
    */
    type : null,

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
    * Create element and add listener
    */
    prepare : function () {

        var input = document.createElement('INPUT');

        input.type = 'file';
        input.addEventListener('change', this.fileSelected);

        this.input = input;

    },

    /**
    * Clear input and type. Ready for getting new file
    */
    clearInput : function () {

        /** Remove old input */
        this.input = null;

        this.type = null;

    },

    /**
    * Choose-file button click handler
    */
    selectFile : function (event, type) {

        this.prepare();

        this.type = type;

        this.input.click();

    },

    /**
    * Send file to server when select
    */
    fileSelected : function () {

        var type        = transport.type,
            input       = this,
            files       = input.files,
            formData    = new FormData();

        formData.append('type', type);

        formData.append('files', files[0], files[0].name);

        codex.ajax.call({
            type : 'POST',
            url : transport.transportURL,
            data : formData,
            success : transport.responseForPageForm,
            beforeSend : transport.beforeSendPageForm,
        });

        transport.clearInput();

    },

    beforeSendPageForm : function () {

        // add loader

    },

    /**
    * Save file info into page form or show exception
    */
    responseForPageForm : function (response) {

        // stop loader

        response = JSON.parse(response);

        if (response.success && response.title) {

            transport.storeFile(response);

        } else {

            codex.alerts.show(response.message);

        }

    },

    /**
    * Store file in memory
    * Attaches list will be sent form submitting
    */
    storeFile : function (file) {

        if (!file || !file.id) return;

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

        }, 200);


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

    },

};

module.exports = transport;
