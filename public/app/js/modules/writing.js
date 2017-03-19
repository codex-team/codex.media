module.exports = (function () {

    var writing = {

        settings_ : {
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

            writing.mergeSettings_(settings);

            loadEditorResources(writing.settings_.plugins, function () {

                return Promise.resolve();

            });

        },

        /**
         * Fill module's settings by settings from params
         *
         * @param  {Object} settings  list of params from init
         */
        mergeSettings_ : function (settings) {

            for (var key in settings) {

                writing.settings_[key] = settings[key];

            }

        },

        // /**
        //  * Function for create form or editor's target and load editor's sources
        //  */
        // init : function (targetClicked, formId, hidePlaceholderClass) {
        //
        //     /** 1. Create form or textarea for editor */
        //     var target = document.getElementById(writing.settings_.targetId);
        //
        //     writing.appendTextareasToTarget_(target);
        //
        //     writing.startEditor_();
        //
        // },

        /**
         * Append textareas for codex.editor
         *
         * @param  {Element} target
         */
        appendTextareasToTarget_ : function (target) {

            var textareaHtml, textareaContent;

            textareaHtml = writing.createElem_('TEXTAREA', {
                name   : 'html',
                id     : writing.settings_.textareaId,
                hidden : true,
            }, []);

            textareaContent = writing.createElem_('TEXTAREA', {
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
        createElem_ : function (tag, params, classes) {

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
        startEditor_ : function () {

            codex.editor.start({

                textareaId:  writing.settings_.textareaId,

                initialBlockPlugin : writing.settings_.initialBlockPlugin,

                hideToolbar: writing.settings_.hideEditorToolbar,

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
                    items : writing.settings_.items,
                }
            });

            document.getElementById(writing.settings_.titleId).focus();

        },

        init : function () {

            /** 1. Create form or textarea for editor */
            var target = document.getElementById(writing.settings_.targetId);

            writing.appendTextareasToTarget_(target);
            writing.startEditor_();

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
            version_ : '1.5',

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

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources.version_ + '/plugins/' + plugin + '/' + plugin,
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

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources.version_ + '/codex-editor',
                    scriptUrl = url + '.js',
                    styleUrl  = url + '.css';

                return Promise.resolve()
                    .then(codex.loader.importScript(scriptUrl, 'editor-core'))
                    .then(codex.loader.importStyle(styleUrl, 'editor-core'));

            }

        };

        return editorResources.load;

    })();

    return {
        init    : writing.init,
        prepare : writing.prepare,
        open    : writing.open,
    };

})();
