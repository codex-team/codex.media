/**
* File transport module
*/

/* eslint-disable */

var transport = {

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

        this.form  = document.getElementById('transportForm');
        this.input = document.getElementById('transportInput');

        if (!this.form || !this.input) {
            return false;
        }

        this.input.addEventListener('change', this.fileSelected);

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
    storeFile : function(file){

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
            row          = document.createElement('div');
            filename     = document.createElement('span');
            deleteButton = document.createElement('span');

        row.classList.add('item');

        switch (file.type){
            case '1': filename.classList.add('item_file'); break;
            case '2': filename.classList.add('item_image'); break;
            default: break;
        }

        filename.textContent = file.title;
        filename.setAttribute('contentEditable', true);

        deleteButton.classList.add('fl_r', 'button-delete', 'icon-trash');
        deleteButton.addEventListener('click', function(){

            if (this.parentNode.dataset.ready_for_delete) {

                delete codex.transport.files[file.id];
                this.parentNode.remove();
            }

            this.parentNode.dataset.ready_for_delete = true;
            this.classList.add('button-delete__ready-to-delete');
            this.innerHTML = 'Удалить документ';

        } , false);

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
    storeFileName : function(){

        /**
        * Clear previous keydown-timeout
        */
        if (codex.transport.keydownFinishedTimeout){
            clearTimeout(codex.transport.keydownFinishedTimeout);
        }

        var input = this;

        /**
        * Start waiting to input finished, then save value to this.files
        */
        codex.transport.keydownFinishedTimeout = setTimeout(function() {

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
    submitAtlasForm : function(){

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

        setTimeout(function () {

            JSONinput.innerHTML = JSON.stringify(codex.editor.state.jsonOutput);

            /**
             * Send form
             */
            atlasForm.submit();

        }, 100);

    }
};

module.exports = transport;
