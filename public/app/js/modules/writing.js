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

    var writing = {

        editorIsReady : false,

        settings : {
            hideEditorToolbar  : false,
            titleId            : 'editorWritingTitle',
            initialBlockPlugin : 'paragraph',
            items              : [],
            resources : [
                { name : 'codex-editor', path : {
                    script : 'https://cdn.ifmo.su/editor/v1.6/codex-editor.js',
                    style  : 'https://cdn.ifmo.su/editor/v1.6/codex-editor.css',
                }},

                { name : 'paragraph', path : {
                    script : 'https://cdn.ifmo.su/editor/v1.6/plugins/paragraph/paragraph.js',
                    style  : 'https://cdn.ifmo.su/editor/v1.6/plugins/paragraph/paragraph.css',
                }},
                { name : 'header', path : {
                    script : 'https://cdn.ifmo.su/editor/v1.6/plugins/header/header.js',
                    style  : 'https://cdn.ifmo.su/editor/v1.6/plugins/header/header.css',
                }},
            ],

            holderId : null,
            pageId   : 0,
            parentId : 0,
        },

        /**
         * Prepare editor's resourses
         *
         * @param  {Object} settings    base settings for editor
         * @return {Promise}            all editor's resources are ready
         */
        prepare : function (settings) {

            writing.mergeSettings(settings);

            return loadEditorResources(writing.settings.resources)
                .then(function () {

                    writing.editorIsReady = true;

                });

        },

        /**
         * Fill module's settings by settings from params
         *
         * @param  {Object} settings  list of params from init
         */
        mergeSettings : function (settings) {

            for (var key in settings) {

                writing.settings[key] = settings[key];

            }

        },

        /**
         * Run editor
         */
        startEditor : function () {

            codex.editor.start({

                holderId:  writing.settings.holderId,

                initialBlockPlugin : writing.settings.initialBlockPlugin,

                hideToolbar: writing.settings.hideEditorToolbar,

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
                    items : writing.settings.items,
                }
            });

            document.getElementById(writing.settings.titleId).focus();

        },

        /**
         * Public function for run editor
         */
        init : function () {

            if (!writing.editorIsReady) return;

            writing.startEditor();

        },

        /**
         * Show form and hide placeholder
         *
         * @param  {Element} targetClicked       placeholder with wrapper
         * @param  {String} formId               remove 'hide' from this form by id
         * @param  {String} hidePlaceholderClass add this class to placeholder
         */
        open : function (targetClicked, formId, hidePlaceholderClass) {

            if (!writing.editorIsReady) return;

            var holder = targetClicked;

            document.getElementById(formId).classList.remove('hide');
            holder.classList.add(hidePlaceholderClass);
            holder.onclick = null;

            writing.init();

        },

    };

    /**
     * Load editor resources and append block with them to body
     *
     * @param  {Array}    plugins list of plugins to load
     * @return {Promise}
     */
    var loadEditorResources = (function () {

        /**
         * @param  {Array} resources list of resources which should be loaded
         */
        var load = function (resources) {

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

        return load;

    })();

    var submitForm = (function () {

        return {

            /**
            * Prepares and submit form
            * Send attaches by json-encoded stirng with hidden input
            */
            submit : function () {

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

                        JSONinput.innerHTML = '';

                    } else {

                        JSONinput.innerHTML = JSON.stringify({ data: codex.editor.state.jsonOutput });

                    }

                    /**
                     * Send form
                     */
                    atlasForm.submit();

                }, 100);

            },

            /**
            * Submits editor form for opening in full-screan page without saving
            */
            openEditorFullscreen : function () {

                var atlasForm = document.forms.atlas,
                    openEditorFlagInput = document.createElement('input');

                openEditorFlagInput.type = 'hidden';
                openEditorFlagInput.name = 'openFullScreen';
                openEditorFlagInput.value = 1;

                atlasForm.append(openEditorFlagInput);

                this.submit();

            },

        };

    })();

    return {
        init    : writing.init,
        prepare : writing.prepare,
        open    : writing.open,
        openEditorFullscreen : submitForm.openEditorFullscreen,
        submit               : submitForm.submit,
    };

})();
