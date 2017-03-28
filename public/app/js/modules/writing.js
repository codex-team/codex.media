/**
 * Module for load and start codex-editor
 *
 * Using:
 *
 * codex.writing.prepare({
 *     holderId : 'placeForEditor',                                         // (required)
 *     hideEditorToolbar : <?= $hideEditorToolbar ? 'true' : 'false' ?>,
 *     items : <?= json_encode($page->blocks) ?: '[]' ?>,
 *     pageId   : <?= $page->id ?>,
 *     parentId : <?= $page->id_parent ?>,
 * }).then(
 *    codex.writing.init
 * );
 */


module.exports = (function () {

    var editorIsReady = false,
        submitButton = null,
        settings = {
            hideEditorToolbar   : false,
            titleId             : 'editorWritingTitle',
            initialBlockPlugin  : 'paragraph',
            data                : {items: []},
            resources           : [],
            holderId            : null,
            pageId              : 0,
            parentId            : 0,
        };

    /**
     * Prepare editor's resourses
     *
     * @param  {Object} initSettings    base settings for editor
     * @return {Promise}            all editor's resources are ready
     */
    var prepare = function (initSettings) {

        mergeSettings(initSettings);

        return loadEditorResources(settings.resources)
                .then(function () {

                    editorIsReady = true;

                });

    };

    /**
     * Fill module's settings by settings from params
     *
     * @param  {Object} initSettings  list of params from init
     */
    function mergeSettings(initSettings) {

        for (var key in initSettings) {

            settings[key] = initSettings[key];

        }

    }

    /**
     * Run editor
     */
    function startEditor() {

        /**
         * @todo get from server
         */
        var EDITOR_IMAGE = 1;
        var EDITOR_FILE  = 2;

        codex.editor.start({

            holderId:  settings.holderId,

            initialBlockPlugin : settings.initialBlockPlugin,

            hideToolbar: settings.hideEditorToolbar,

            tools : {
                paragraph: {
                    type               : 'paragraph',
                    iconClassname      : 'ce-icon-paragraph',
                    render             : window.paragraph.render,
                    validate           : window.paragraph.validate,
                    save               : window.paragraph.save,
                    allowedToPaste     : true,
                    showInlineToolbar  : true,
                    destroy            : window.paragraph.destroy,
                    allowRenderOnPaste : true
                },
                header: {
                    type             : 'header',
                    iconClassname    : 'ce-icon-header',
                    appendCallback   : window.header.appendCallback,
                    makeSettings     : window.header.makeSettings,
                    render           : window.header.render,
                    validate         : window.header.validate,
                    save             : window.header.save,
                    destroy          : window.header.destroy,
                    displayInToolbox : true
                },
                image: {
                    type                  : 'image',
                    iconClassname         : 'ce-icon-picture',
                    appendCallback        : window.image.appendCallback,
                    prepare               : window.image.prepare,
                    makeSettings          : window.image.makeSettings,
                    render                : window.image.render,
                    save                  : window.image.save,
                    destroy               : window.image.destroy,
                    isStretched           : true,
                    showInlineToolbar     : true,
                    displayInToolbox      : true,
                    renderOnPastePatterns : window.image.pastePatterns,
                    config: {
                        uploadImage : '/upload/' + EDITOR_IMAGE,
                        uploadFromUrl : ''
                    }
                },
                attaches: {
                    type             : 'attaches',
                    displayInToolbox : true,
                    iconClassname    : 'cdx-attaches__icon',
                    prepare          : window.cdxAttaches.prepare,
                    render           : window.cdxAttaches.render,
                    save             : window.cdxAttaches.save,
                    validate         : window.cdxAttaches.validate,
                    destroy          : window.cdxAttaches.destroy,
                    appendCallback   : window.cdxAttaches.appendCallback,
                    config: {
                        fetchUrl: '/upload/' + EDITOR_FILE,
                        maxSize: 25000,
                    }
                }
            },

            data : settings.data
        });

        document.getElementById(settings.titleId).focus();

    }

    /**
     * Public function for run editor
     */
    var init = function () {

        if (!editorIsReady) return;

        startEditor();

    };

    /**
     * Show form and hide placeholder
     *
     * @param  {Element} targetClicked       placeholder with wrapper
     * @param  {String}  formId               remove 'hide' from this form by id
     * @param  {String}  hidePlaceholderClass add this class to placeholder
     */
    var open = function (targetClicked, formId, hidePlaceholderClass) {

        if (!editorIsReady) return;

        var holder = targetClicked;

        document.getElementById(formId).classList.remove('hide');
        holder.classList.add(hidePlaceholderClass);
        holder.onclick = null;

        init();

    };

    /**
     * Load editor resources and append block with them to body
     *
     * @param  {Array} resources list of resources which should be loaded
     * @return {Promise}
     */
    var loadEditorResources = function (resources) {

        return Promise.all(
            resources.map(loadResource)
        );

    };

    /**
     * Loads resource
     *
     * @param  {Object} resource name and paths for js and css
     * @return {Promise}
     */
    function loadResource(resource) {

        var name      = resource.name,
            scriptUrl = resource.path.script,
            styleUrl  = resource.path.style;

        return Promise.all([
            codex.loader.importScript(scriptUrl, name),
            codex.loader.importStyle(styleUrl, name)
        ]);

    }

    /**
    * Prepares form to submit
    */
    var getForm = function () {

        var atlasForm = document.forms.atlas;

        if (!atlasForm) return;

        /** CodeX.Editor */
        var JSONinput = document.createElement('TEXTAREA');

        JSONinput.name   = 'content';
        JSONinput.id     = 'json_result';
        JSONinput.hidden = true;
        atlasForm.appendChild(JSONinput);

        /**
         * Save blocks
         */
        codex.editor.saver.saveBlocks();

        return atlasForm;

    };

    /**
     * Send ajax request with writing form data
     * @param button - submit button (needed to add loading animation)
     */
    var submit = function (button) {

        var title = document.forms.atlas.elements['title'],
            form;

        if (title.value.trim() === '') {

            codex.editor.notifications.notification({
                type: 'warn',
                message: 'Заполните заголовок'
            });

            return;

        }

        form = getForm();

        submitButton = button;

        submitButton.classList.add('loading');

        window.setTimeout(function () {

            form.elements['content'].innerHTML = JSON.stringify({items: codex.editor.state.jsonOutput});

            codex.ajax.call({
                url: '/p/save',
                data: new FormData(form),
                success: submitResponse,
                type: 'POST'
            });

        }, 500);

    };

    /**
     * Response handler for page saving
     * @param response
     */
    var submitResponse = function (response) {

        submitButton.classList.remove('loading');

        response = JSON.parse(response);

        if (response.success) {

            window.location = response.redirect;
            return;

        }

        codex.editor.notifications.notification({
            type: 'warn',
            message: response.message
        });

    };

    /**
    * Submits writing form for opening in full-screan page without saving
    */
    var openEditorFullscreen = function () {


        var form = getForm();

        window.setTimeout(function () {

            form.elements['content'].innerHTML = JSON.stringify({ items: codex.editor.state.jsonOutput });

            form.submit();

        }, 500);

    };

    return {
        init    : init,
        prepare : prepare,
        open    : open,
        openEditorFullscreen : openEditorFullscreen,
        submit               : submit,
    };

})();
