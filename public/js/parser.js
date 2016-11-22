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
