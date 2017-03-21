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
        settings = {
            hideEditorToolbar   : false,
            titleId             : 'editorWritingTitle',
            initialBlockPlugin  : 'paragraph',
            items               : [],
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

    };

    /**
     * Run editor
     */
    function startEditor() {

        codex.editor.start({

            holderId:  settings.holderId,

            initialBlockPlugin : settings.initialBlockPlugin,

            hideToolbar: settings.hideEditorToolbar,

            tools : {
                paragraph: {
                    type: 'paragraph',
                    iconClassname: 'ce-icon-paragraph',
                    render: paragraph.render,
                    validate: paragraph.validate,
                    save: paragraph.save,
                    allowedToPaste: true,
                    showInlineToolbar: true,
                    destroy: paragraph.destroy,
                    allowRenderOnPaste: true
                },
                header: {
                    type: 'header',
                    iconClassname: 'ce-icon-header',
                    appendCallback: header.appendCallback,
                    makeSettings: header.makeSettings,
                    render: header.render,
                    validate: header.validate,
                    save: header.save,
                    destroy: header.destroy,
                    displayInToolbox: true
                },
            },

            data : {
                items : settings.items,
            }
        });

        document.getElementById(settings.titleId).focus();

    };

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

    };

    /**
    * Prepares and submit form
    * Send attaches by json-encoded stirng with hidden input
    */
    var submit = function () {

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

        window.setTimeout(function () {

            var blocksCount = codex.editor.state.jsonOutput.length;

            if (!blocksCount) {

                JSONinput.innerHTML = JSON.stringify({ data : [] });

            } else {

                JSONinput.innerHTML = JSON.stringify({ data: codex.editor.state.jsonOutput });

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
    var openEditorFullscreen = function () {

        var atlasForm = document.forms.atlas,
            openEditorFlagInput = document.createElement('input');

        openEditorFlagInput.type = 'hidden';
        openEditorFlagInput.name = 'openFullScreen';
        openEditorFlagInput.value = 1;

        atlasForm.append(openEditorFlagInput);

        this.submit();

    };

    return {
        init    : init,
        prepare : prepare,
        open    : open,
        openEditorFullscreen : openEditorFullscreen,
        submit               : submit,
    };

})();
