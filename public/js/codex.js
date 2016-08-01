var codex = (function(codex){

    codex.settings = {};

    /**
    * Preparation method
    */
    codex.init = function(settings){

        /** Save settings or use defaults */
        for (var set in settings ){
            this.settings[set] = settings[set] || this.settings[set] || null;
        }

    };

    return codex;


})({});

/**
* Handle document ready
*/
codex.documentIsReady = function(f){
    return /in/.test(document.readyState) ? setTimeout(codex.documentIsReady,9,f) : f();
};

/**
* System methods and helpers
*/
codex.core = {

    /**
    * Native ajax method.
    */
    ajax : function (data) {

        if (!data || !data.url){
            return;
        }

        var XMLHTTP          = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"),
            success_function = function(){};

        data.async           = true;
        data.type            = data.type || 'GET';
        data.data            = data.data || '';
        data['content-type'] = data['content-type'] || 'application/json; charset=utf-8';
        success_function     = data.success || success_function ;

        if (data.type == 'GET' && data.data) {
            data.url = /\?/.test(data.url) ? data.url + '&' + data.data : data.url + '?' + data.data;
        }

        if (data.withCredentials) {
            XMLHTTP.withCredentials = true;
        }

        if (data.beforeSend && typeof data.beforeSend == 'function') {
            data.beforeSend.call();
        }

        XMLHTTP.open( data.type, data.url, data.async );
        XMLHTTP.setRequestHeader("Content-type", data['content-type'] );
        XMLHTTP.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        XMLHTTP.onreadystatechange = function() {
            if (XMLHTTP.readyState == 4 && XMLHTTP.status == 200) {
                success_function(XMLHTTP.responseText);
            }
        };

        XMLHTTP.send(data.data);

    },

    showException : function ( message ){

        var wrapper = document.querySelector('.exceptionWrapper'),
            notify;

        if (!wrapper) {

            wrapper = document.createElement('div');
            wrapper.classList.add('exceptionWrapper');

            document.body.appendChild(wrapper);

        }

        notify = document.createElement('div');
        notify.classList.add('clientException');

        notify.innerHTML = message;

        wrapper.appendChild(notify);

        notify.classList.add('bounceIn');

        setTimeout(function(){
            notify.remove();
        }, 8000);

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

         this.input.addEventListener('paste', function () {

             _this.inputPasteCallback();

         }, false);

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

                    codex.core.showException('Не удалось импортировать страницу');

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

        if (response.success && response.filename) {

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

        switch (file.type){
            case '1': row.classList.add('item_file'); break;
            case '2': row.classList.add('item_image'); break;
            default: break;
        }

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

codex.appender = {

    /* Pagination. Here is a number of current page */
    page : 1,

    settings : null,

    block_for_items : null,

    load_more_button : null,

    /**
     * Button's text for saving it.
     * On its place dots will be  while news are loading
     */
    button_text : null,

    init : function (settings)
    {
        codex.appender.settings    = settings;

        /* Checking for existing button and field for loaded info */
        codex.appender.load_more_button = document.getElementById(codex.appender.settings.button_id);
        if ( !codex.appender.load_more_button ) return false;

        codex.appender.block_for_items = document.getElementById(codex.appender.settings.target_block_id);
        if ( !codex.appender.block_for_items ) return false;

        codex.appender.page        = settings.current_page;
        codex.appender.button_text = codex.appender.load_more_button.innerHTML;

        codex.appender.load_more_button.addEventListener('click', function (event){

            codex.appender.load();

            event.preventDefault();

            codex.appender.auto_loading.init();

        }, false);

    },

    load : function ()
    {
        var request_url = codex.appender.settings.url + (parseInt(codex.appender.page) + 1);

        codex.core.ajax({
            type: 'post',
            url: request_url,
            data: {},
            beforeSend : function ()
            {
                codex.appender.load_more_button.innerHTML = ' ';
                codex.appender.load_more_button.classList.add('loading');
            },
            success : function(response)
            {
                response = JSON.parse(response);

                if ( response.success )
                {
                    codex.appender.block_for_items.innerHTML += response.pages;

                    /* Next page */
                    codex.appender.page++;

                    /* Removing restriction for auto loading */
                    codex.appender.auto_loading.can_load = true;

                    /* Checking for next page's existing. If no — hide the button for loading news and remove listener */
                    if ( !response.next_page ) codex.appender.disable();

                } else {

                    codex.core.showException('Не удалось подгрузить новости');

                }

                codex.appender.load_more_button.classList.remove('loading');
                codex.appender.load_more_button.innerHTML = codex.appender.button_text;
            }
        });
    },

    disable : function ()
    {
        codex.appender.load_more_button.style.display = "none";

        if ( codex.appender.auto_loading.is_launched )
        {
            codex.appender.auto_loading.disable();
        }
    },

    auto_loading : {

        is_launched : false,

        /**
         * Possibility to load news by scrolling.
         * Restriction for reduction requests which could be while scrolling
         */
        can_load : true,

        init : function ()
        {
            window.addEventListener("scroll", codex.appender.auto_loading.scrollEvent);

            codex.appender.auto_loading.is_launched = true;
        },

        disable : function ()
        {
            window.removeEventListener("scroll", codex.appender.auto_loading.scrollEvent);

            codex.appender.auto_loading.is_launched = false;
        },

        scrollEvent : function ()
        {
            var scroll_reached_end = window.pageYOffset + window.innerHeight >= document.height;

            if (scroll_reached_end && codex.appender.auto_loading.can_load)
            {
                codex.appender.auto_loading.can_load = false;

                codex.appender.load();
            }
        },

    },

};


codex.content = {

    /**
    * Module uses for toggle custom checkboxes
    * that has 'js-custom-checkbox' class and input[type="checkbox"] included
    * Example:
    * <span class="js-custom-checkbox">
    *    <input type="checkbox" name="" value="1"/>
    * </span>
    */
    customCheckboxes : {

        /**
        * This class specifies checked custom-checkbox
        * You may set it on serverisde
        */
        CHECKED_CLASS : 'checked',

        init : function(){

            var checkboxes = document.getElementsByClassName('js-custom-checkbox');

            if (checkboxes.length) for (var i = checkboxes.length - 1; i >= 0; i--) {
                checkboxes[i].addEventListener('click', codex.content.customCheckboxes.clicked , false);
            }
        },

        clicked : function(){

            var checkbox  = this,
                input     = this.querySelector('input'),
                isChecked = this.classList.contains(codex.content.customCheckboxes.CHECKED_CLASS);

            checkbox.classList.toggle(codex.content.customCheckboxes.CHECKED_CLASS);

            if (isChecked) {
                input.removeAttribute('checked');
            } else {
                input.setAttribute('checked', 'checked');
            }

        }

    }

};



codex.documentIsReady(function(){

    codex.transport.init();

    codex.content.customCheckboxes.init();

    // codex.parser.init({
    //     input_id : 'parser_input_url'
    // });
});


