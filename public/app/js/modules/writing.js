module.exports = (function () {

    var writing = {

        plugins : [
            'paragraph',
            'header',
            'code'
        ],

        settings : {

            hideEditorToolbar  : false,
            textareaId         : 'codexEditor_time' + Date.now(),
            initialBlockPlugin : 'paragraph',
            items              : [],

            targetId : null,
            pageId   : null,
            parentId : null,

        },

        form : null,

        init : function (settings) {

            writing.mergeSettings_(settings);

            writing.createEditor_();

        },

        mergeSettings_ : function (settings) {

            for (var key in settings) {

                writing.settings[key] = settings[key];

            }

        },

        createEditor_ : function () {

            /** 1. Create form */
            // writing.createForm_();
            var target = document.getElementById(writing.settings.targetId);

            writing.appendWrapperWithTextareasToTarget_(target);

            /** 2. Load resources */
            loadEditorResources(writing.plugins, (function () {

                /** After load */
                writing.startEditor_();

            }));

        },

        // createForm_ : function () {
        //
        //     var target = document.getElementById(writing.settings.targetId);
        //
        //     writing.form = document.createElement('FORM'),
        //     writing.form.action = '/p/writing';
        //     writing.form.method = 'post';
        //     writing.form.classList.add('writing', 'island');
        //     writing.createAndAppendFormElem_('csrf', window.csrf);
        //     writing.createAndAppendFormElem_('id', writing.settings.pageId);
        //     writing.createAndAppendFormElem_('id_parent', writing.settings.parentId);
        //     writing.appendWrapperWithTextareasToTarget_(writing.form);
        //
        //     target.appendChild(writing.form);
        //
        // },
        //
        // createAndAppendFormElem_ : function (name, value) {
        //
        //     var elem = document.createElement('INPUT');
        //
        //     elem.name   = name;
        //     elem.value  = value;
        //     elem.hidden = true;
        //
        //     writing.form.appendChild(elem);
        //
        // },

        appendWrapperWithTextareasToTarget_ : function (target) {

            console.log(target);

            var textareaHtml    = document.createElement('TEXTAREA'),
                textareaContent = document.createElement('TEXTAREA');

            textareaHtml.name = 'html';
            textareaHtml.id = writing.settings.textareaId;
            textareaHtml.hidden = true;

            textareaContent.name = 'content';
            textareaContent.id = 'json_result';
            textareaContent.hidden = true;

            target.appendChild(textareaHtml);
            target.appendChild(textareaContent);

        },

        startEditor_ : function () {

            codex.editor.start({

                textareaId:  writing.settings.textareaId,

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

        },

    };

    window.filesLeftToLoad = 0;

    /**
     * Load editor resources and append block with them to body
     *
     * @param  {Array}    plugins — list of plugins to load
     * @param  {Function} onLoad  — function to run after loading resources
     */
    var loadEditorResources = (function () {

        var editorResources = {

            version_ : '1.5',

            nodes_ : {

                editor : {
                    script : null,
                    style : null,
                },
                plugins : [],

            },

            loadFunction_ : null,

            load : function (plugins, onLoad) {

                editorResources.loadFunction_ = onLoad;

                var resources;

                editorResources.loadCore_();

                for (var i = 0; i < plugins.length; i++) {

                    editorResources.loadPlugin_(plugins[i]);

                }

                window.filesLeftToLoad = 1 + plugins.length;

                resources = editorResources.createPackage_();
                document.body.appendChild(resources);

            },

            loadCore_ : function () {

                var url = 'https://cdn.ifmo.su/editor/v' + editorResources.version_ + '/codex-editor',
                    scriptUrl = url + '.js',
                    styleUrl  = url + '.css';

                editorResources.nodes_.editor.script = editorResources.loadResource_('script', scriptUrl);
                editorResources.nodes_.editor.style  = editorResources.loadResource_('style', styleUrl);

            },

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
            * @param  {String} type — 'style' or 'script'
            * @param  {String} url  — path to the resource
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
        init : writing.init,
    };

})();
