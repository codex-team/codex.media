module.exports = (function () {

    var writing = {

        _settings : {
            hideEditorToolbar  : false,
            textareaId         : 'codexEditor_time' + Date.now(),
            titleId            : 'editorWritingTitle',
            initialBlockPlugin : 'paragraph',
            items              : [],
            plugins : [
                'paragraph',
                'header',
            ],

            targetId : null,
            pageId   : null,
            parentId : null,
        },

        form_ : null,

        prepare : function (settings) {

            writing._mergeSettings(settings);

            loadEditorResources(writing._settings.plugins, function () {

                return Promise.resolve();

            });

        },

        /**
         * Fill module's settings by settings from params
         *
         * @param  {Object} settings  list of params from init
         */
        _mergeSettings : function (settings) {

            for (var key in settings) {

                writing._settings[key] = settings[key];

            }

        },

        // /**
        //  * Function for create form or editor's target and load editor's sources
        //  */
        // init : function (targetClicked, formId, hidePlaceholderClass) {
        //
        //     /** 1. Create form or textarea for editor */
        //     var target = document.getElementById(writing._settings.targetId);
        //
        //     writing._appendTextareasToTarget(target);
        //
        //     writing._startEditor();
        //
        // },

        /**
         * Append textareas for codex.editor
         *
         * @param  {Element} target
         */
        _appendTextareasToTarget : function (target) {

            var textareaHtml, textareaContent;

            textareaHtml = writing._createElem('TEXTAREA', {
                name   : 'html',
                id     : writing._settings.textareaId,
                hidden : true,
            }, []);

            textareaContent = writing._createElem('TEXTAREA', {
                name   : 'content',
                id     : 'json_result',
                hidden : true,
            }, []);

            target.appendChild(textareaHtml);
            target.appendChild(textareaContent);

        },

        /**
         * Create element and return it
         *
         * @param  {String} tag
         * @param  {Object} params  pairs { key: value, ... }
         * @param  {Array} classes  list of classes for new element
         * @return {Element}
         */
        _createElem : function (tag, params, classes) {

            var elem = document.createElement(tag);

            for (var param in params) {

                elem[param] = params[param];

            }

            for (var i in classes) {

                elem.classList.add(classes[i]);

            }

            return elem;

        },

        /**
         * Run editor
         */
        _startEditor : function () {

            console.log(codex.editor);

            codex.editor.start({

                textareaId:  writing._settings.textareaId,

                initialBlockPlugin : writing._settings.initialBlockPlugin,

                hideToolbar: writing._settings.hideEditorToolbar,

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
                    items : writing._settings.items,
                }
            });

            document.getElementById(writing._settings.titleId).focus();

        },

        init : function () {

            /** 1. Create form or textarea for editor */
            var target = document.getElementById(writing._settings.targetId);

            console.log(target);

            writing._appendTextareasToTarget(target);
            writing._startEditor();

        },

        /**
         * Show form and hide placeholder
         *
         * @param  {Element} targetClicked       placeholder with wrapper
         * @param  {String} formId               remove 'hide' from this form by id
         * @param  {String} hidePlaceholderClass add this class to placeholder
         */
        open : function (targetClicked, formId, hidePlaceholderClass) {

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
     * @param  {Function} onLoad  function to run after loading resources
     */
    var loadEditorResources = (function () {

        var editorResources = {

            /**
             * Editor's version
             */
            _version : '1.5',

            /**
             * Variable for function that should be runned onLoad all resources
             */
            _loadFunction : null,

            /**
             * Init function for load editor's resources
             *
             * @param  {Array} plugins list of plugins which should be loaded
             * @param  {Function} onLoad  call this function when all editor's files are ready
             */
            load : function (plugins, onLoad) {

                /** Set function which should be runned when resources are load */
                editorResources._loadFunction = onLoad;

                var queue = Promise.resolve();

                queue.then(
                    editorResources._loadCore()
                );

                for (var i = 0; i < plugins.length; i++) {

                    queue.then(
                        editorResources._loadPlugin(plugins[i])
                    );

                };

                queue.then(function () {

                    editorResources._loadFunction();

                    Promise.resolve();

                });

            },

            _loadPlugin : function (plugin) {

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources._version + '/plugins/' + plugin + '/' + plugin,
                    scriptUrl = url + '.js',
                    styleUrl  = url + '.css';

                return Promise.resolve()
                    .then(codex.loader.importScript(scriptUrl, plugin))
                    .then(codex.loader.importStyle(styleUrl, plugin));

            },

            /**
             * Add editor core script and style to the editorResources.nodes_.editor
             */
            _loadCore : function () {

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources._version + '/codex-editor',
                    scriptUrl = url + '.js',
                    styleUrl  = url + '.css';

                return Promise.resolve()
                    .then(codex.loader.importScript(scriptUrl, 'editor-core'))
                    .then(codex.loader.importStyle(styleUrl, 'editor-core'));

            }

        };

        return editorResources.load;

    })();

    var submitForm = (function () {

        return {

            /**
            * Prepares and submit form
            * Send attaches by json-encoded stirng with hidden input
            */
            submitAtlasForm : function () {

                var atlasForm = document.forms.atlas;

                if (!atlasForm) return;

                /** CodeX.Editor */
                var JSONinput = document.getElementById('json_result');

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

                this.submitAtlasForm();

            },

        };

    })();

    return {
        init    : writing.init,
        prepare : writing.prepare,
        open    : writing.open,
        openEditorFullscreen : submitForm.openEditorFullscreen,
        submitAtlasForm      : submitForm.submitAtlasForm,
    };

})();
