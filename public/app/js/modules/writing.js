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

        init : function (settings) {

            writing.mergeSettings_(settings);

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

        /**
         * Function for create form or editor's target and load editor's sources
         */
        createEditor : function () {

            /** 1. Create form or textarea for editor */
            var target = document.getElementById(writing.settings_.targetId);

            writing.appendTextareasToTarget_(target);

            /** 2. Load resources */
            loadEditorResources(writing.settings_.plugins, (function () {

                /** After load */
                writing.startEditor_();

            }));

        },

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

        /**
         * Show form and hide placeholder
         *
         * @param  {Element} targetClicked       placeholder with wrapper
         * @param  {String} formId               remove 'hide' from this form by id
         * @param  {String} hidePlaceholderClass add this class to placeholder
         */
        openEditor : function (targetClicked, formId, hidePlaceholderClass) {

            var holder = targetClicked,
                parent = holder.parentNode;

            writing.createEditor();
            document.getElementById(formId).classList.remove('hide');

            parent.classList.add(hidePlaceholderClass);


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
             * Objects with script and style Elements of editor and plugins
             */
            nodes_ : {

                editor : {
                    script : null,
                    style : null,
                },
                plugins : [],

            },

            /**
             * Variable for function that should be runned onLoad all resources
             */
            loadFunction_ : null,

            /**
             * Init function for load editor's resources
             *
             * @param  {Array} plugins list of plugins which should be loaded
             * @param  {Function} onLoad  call this function when all editor's files are ready
             */
            load : function (plugins, onLoad) {

                /** Set function which should be runned when resources are load */
                editorResources.loadFunction_ = onLoad;

                var resources;

                /** Load  */
                editorResources.loadCore_();

                for (var i = 0; i < plugins.length; i++) {

                    editorResources.loadPlugin_(plugins[i]);

                }

                /** Wait for load editor + plugins scripts */
                window.filesLeftToLoad = 1 + plugins.length;

                /** Create and append editor's resources to body */
                resources = editorResources.createPackage_();
                document.body.appendChild(resources);

            },

            /**
             * Add editor core script and style to the editorResources.nodes_.editor
             */
            loadCore_ : function () {

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources.version_ + '/codex-editor',
                    scriptUrl = url + '.js',
                    styleUrl  = url + '.css';

                editorResources.nodes_.editor.script = editorResources.loadResource_('script', scriptUrl);
                editorResources.nodes_.editor.style  = editorResources.loadResource_('style', styleUrl);

            },

            /**
             * Add plugin script and styles Elements to the array editorResources.nodes_.plugins
             *
             * @param  {String} plugin plugin's name
             */
            loadPlugin_ : function (plugin) {

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources.version_ + '/plugins/' + plugin + '/' + plugin,
                    scriptUrl = url + '.js',
                    styleUrl  = url + '.css';

                editorResources.nodes_.plugins.push({
                    'script' : editorResources.loadResource_('script', scriptUrl),
                    'style'  : editorResources.loadResource_('style', styleUrl)
                });

            },

            /**
             * Return element with resource by type
             *
             * @param  {String} type 'style' or 'script'
             * @param  {String} url  path to the resource
             * @return {Element}
             */
            loadResource_ : function (type, url) {

                if (!type || !url) {

                    return false;

                }

                switch (type) {
                    case 'script':
                        return editorResources.loadResourceJS_(url);
                    case 'style':
                        return editorResources.loadResourceCSS_(url);
                    default:
                        return false;
                }

            },

            /**
             * Create and return element with script by url
             *
             * @param  {String} url
             * @return {Element}
             */
            loadResourceJS_ : function (url) {

                var script = document.createElement('SCRIPT');

                script.type   = 'text/javascript';
                script.src    = url;
                script.async  = true;
                script.onload = editorResources.onLoad_;

                return script;

            },

            /**
             * Create and return stylesheet element by url
             *
             * @param  {String} url
             * @return {Element}
             */
            loadResourceCSS_ : function (url) {

                var style = document.createElement('LINK');

                style.type = 'text/css';
                style.href = url;
                style.rel  = 'stylesheet';

                return style;

            },

            /**
             * Return div with all editor scripts and styles
             *
             * @return {Element}
             */
            createPackage_ : function () {

                var divPackage = document.createElement('DIV');

                /** Append core */
                divPackage.appendChild(editorResources.nodes_.editor.script);
                divPackage.appendChild(editorResources.nodes_.editor.style);

                /** Append plugins */
                for (var i = 0; i < editorResources.nodes_.plugins.length; i++) {

                    divPackage.appendChild(editorResources.nodes_.plugins[i].script);
                    divPackage.appendChild(editorResources.nodes_.plugins[i].style);

                };

                return divPackage;

            },

            /**
             * Call loadFunction_ when all resource files are ready
             */
            onLoad_ : function () {

                window.filesLeftToLoad--;

                if (window.filesLeftToLoad === 0) {

                    editorResources.loadFunction_();

                }

            },

        };

        return editorResources.load;

    })();

    return {
        init         : writing.init,
        createEditor : writing.createEditor,
        openEditor   : writing.openEditor,
    };

})();
