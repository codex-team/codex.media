/**
 * Parser module
 * @author Taly Guryn
 */
var parser = {

    input : null,

    init : function () {

        // this.input = document.getElementById(settings.input_id);

        var _this = this;

        this.input.addEventListener('paste', function () {

            _this.inputPasteCallback();

        }, false);

    },

    inputPasteCallback : function () {

        var e = this.input;

        var _this = this;

        window.setTimeout(function () {

            _this.sendRequest(e.value);

        }, 100);

    },


    sendRequest : function (url) {

        codex.core.ajax({
            type: 'get',
            url: '/ajax/get_page',
            data: { 'url' : url },
            success: function (response) {

                var title, content, sourceLink;

                if ( response.success == 1) {

                    title = document.getElementById('page_form_title');
                    content = document.getElementById('page_form_content');
                    sourceLink = document.getElementById('source_link');

                    title.value = response.title;
                    content.value = response.article;
                    sourceLink.value = url;

                    // while we have no own editor, we should use this getting element
                    // cause I can't edit code for external editor
                    document.getElementsByClassName('redactor_redactor')[0].innerHTML = response.article;

                } else {

                    codex.core.showException('Не удалось импортировать страницу');

                }

            }

        });

    }
};

module.exports = parser;
