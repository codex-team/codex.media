var codex = (function(codex){

    codex.settings = {};

    /**
    * Preparation method
    */
    codex.init = function(settings){

        /** Save settings or use defaults */
        for ( set in settings ){
            this.settings[set] = settings[set] || this.settings[set] || null;
        }

    };

    return codex;


})({});

/**
* System methods and helpers
*/
codex.core = {

    /**
    * Native ajax method.
    */
    ajax : function (data) {

        if (!data || !data['url']){
            return;
        }

        var XMLHTTP          = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"),
            success_function = function( r ){};

        data['async']        = !!data['async'];
        data['type']         = data['type'] || 'GET';
        data['data']         = data['data'] || '';
        data['content-type'] = data['content-type'] || 'text/html';
        success_function     = data['success'] || success_function ;

        if (data['type'] == 'GET' && data['data']) {
            data['url'] = /\?/.test(data['url']) ? data['url'] + '&' + data['data'] : data['url'] + '?' + data['data'];
        }

        if (data['withCredentials']) {
            XMLHTTP.withCredentials = true;
        }

        XMLHTTP.open( data['type'], data['url'], data['async'] );
        XMLHTTP.setRequestHeader("Content-type", data['content-type'] );
        XMLHTTP.onreadystatechange = function() {
            if (XMLHTTP.readyState == 4 && XMLHTTP.status == 200) {
                success_function(XMLHTTP.responseText);
            }
        }

        XMLHTTP.send(data['data']);

    }

},


/**
 * Parser module
 * @author Taly Guryn
 */
codex.parser = {

    input : null,

    init : function (settings){

        console.log(this);

         // this.input = document.getElementById(settings.input_id);

         var _this = this;

         this.input.addEventListener('paste', function (event) {

             _this.inputPasteCallback()

         } , false)

    },

    inputPasteCallback : function () {

        var e = this.input;

        var _this = this;

        setTimeout(function(){

            _this.sendRequest(e.value);

        }, 100);
    },


    sendRequest : function (url) {

        codex.core.ajax({
            type: 'get',
            url: '/ajax/get_page',
            data: { 'url' : url },
            success: function(response){

                if ( response.success == 1) {

                    var title = document.getElementById('page_form_title');
                    title.value = response.title;

                    var content = document.getElementById('page_form_content');
                    content.value = response.article;

                    var source_link = document.getElementById('source_link');
                    source_link.value = url;

                    // while we have no own editor, we should use this getting element
                    // cause I can't edit code for external editor
                    document.getElementsByClassName('redactor_redactor')[0].innerHTML   = response.article;

                } else {

                    CLIENT.showException('Не удалось импортировать страницу');

                }
            }
        });
    }
};

/**
* File transport module
*/
codex.transport = {

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

    fileSelected : function (event) {
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

        if (response.success && response.filename) {

            this.storeFile(response);
            // this.appendFileToInput(response.filename);
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
            'name'  : file.filename,
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

        var attachesZone = document.getElementById('formAttaches');

        var row = document.createElement('div');

        row.classList.add('item');
        row.textContent = file.title;
        row.setAttribute('contentEditable', true);

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

        atlasForm.submit();

    }




};

function documentIsReady(f){/in/.test(document.readyState) ? setTimeout(documentIsReady,9,f) : f();}

documentIsReady(function(){

    codex.transport.init()

    // codex.parser.init({
    //     input_id : 'parser_input_url'
    // });
});


