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
    * Input where current transport action stored 
    */
    actionInput: null,

    init : function () {

        this.form = document.getElementById('transportForm');
        this.input = document.getElementById('transportInput');

        if (!this.form || !this.input) {
            return false;
        }

        this.input.addEventListener('change', this.fileSelected);

    },

    /**
    * Chose-file button click handler
    */
    selectFile : function (event, action) {

        this.prepareForm({
            action : action
        });
        this.input.click();  
    
    },

    fileSelected : function (event) {
        codex.transport.form.submit();
        codex.transport.clear();
    },

    prepareForm : function (params) {

        if (!this.actionInput) {
            this.actionInput          = document.createElement('input');
            this.actionInput.type   = 'hidden';
            this.actionInput.name = 'action';
            this.form.appendChild(this.actionInput);
        }

        this.actionInput.value = params.action;

    },

    clear : function () {

        this.action = null;
        this.input.value = null;
        
    },

    response : function (response) {

        console.log(response);

        if (response.success && response.filename) {
            this.appendFileRow(response.filename);
        }
    },

    appendFileRow : function (filename) {

        var attachesZone = document.getElementById('formAttaches');
        
        var row = document.createElement('div');

        row.classList.add('item');
        row.textContent = filename;

        attachesZone.appendChild(row);


    }




}

function documentIsReady(f){/in/.test(document.readyState) ? setTimeout(documentIsReady,9,f) : f();}

documentIsReady(function(){

    codex.transport.init()

    // codex.parser.init({
    //     input_id : 'parser_input_url'
    // });
});


