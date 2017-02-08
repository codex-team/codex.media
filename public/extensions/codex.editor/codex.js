/**
 *
 * Codex Editor
 *
 * @author Codex Team
 */

module.exports = (function (editor) {

    'use strict';

    editor.version = VERSION;

    var init = function () {

        editor.core          = require('./modules/core');
        editor.ui            = require('./modules/ui');
        editor.transport     = require('./modules/transport');
        editor.renderer      = require('./modules/renderer');
        editor.saver         = require('./modules/saver');
        editor.content       = require('./modules/content');
        editor.toolbar       = require('./modules/toolbar/toolbar');
        editor.callback      = require('./modules/callbacks');
        editor.draw          = require('./modules/draw');
        editor.caret         = require('./modules/caret');
        editor.notifications = require('./modules/notifications');
        editor.parser        = require('./modules/parser');
        editor.sanitizer     = require('./modules/sanitizer');

    };

    /**
     * @public
     *
     * holds initial settings
     */
    editor.settings = {
        tools     : ['paragraph', 'header', 'picture', 'list', 'quote', 'code', 'twitter', 'instagram', 'smile'],
        textareaId: 'codex-editor',
        uploadImagesUrl: '/editor/transport/',

        // Type of block showing on empty editor
        initialBlockPlugin: 'paragraph'
    };

    /**
     * public
     *
     * Static nodes
     */
    editor.nodes = {
        textarea          : null,
        wrapper           : null,
        toolbar           : null,
        inlineToolbar     : {
            wrapper : null,
            buttons : null,
            actions : null
        },
        toolbox           : null,
        notifications     : null,
        plusButton        : null,
        showSettingsButton: null,
        showTrashButton   : null,
        blockSettings     : null,
        pluginSettings    : null,
        defaultSettings   : null,
        toolbarButtons    : {}, // { type : DomEl, ... }
        redactor          : null
    };

    /**
     * @public
     *
     * Output state
     */
    editor.state = {
        jsonOutput: [],
        blocks    : [],
        inputs    : []
    };

    /**
    * @public
    * Editor plugins
    */
    editor.tools = {};

    /**
     * Initialization
     * @uses Promise cEditor.core.prepare
     * @param {} userSettings are :
     *          - tools [],
     *          - textareaId String
     *          ...
     *
     * Load user defined tools
     * Tools must contain this important objects :
     *  @param {String} type - this is a type of plugin. It can be used as plugin name
     *  @param {String} iconClassname - this a icon in toolbar
     *  @param {Object} make - what should plugin do, when it is clicked
     *  @param {Object} appendCallback - callback after clicking
     *  @param {Element} settings - what settings does it have
     *  @param {Object} render - plugin get JSON, and should return HTML
     *  @param {Object} save - plugin gets HTML content, returns JSON
     *  @param {Boolean} displayInToolbox - will be displayed in toolbox. Default value is TRUE
     *  @param {Boolean} enableLineBreaks - inserts new block or break lines. Default value is FALSE
     *
     * @example
     *   -  type             : 'header',
     *   -  iconClassname    : 'ce-icon-header',
     *   -  make             : headerTool.make,
     *   -  appendCallback   : headerTool.appendCallback,
     *   -  settings         : headerTool.makeSettings(),
     *   -  render           : headerTool.render,
     *   -  save             : headerTool.save,
     *   -  displayInToolbox : true,
     *   -  enableLineBreaks : false
     */
    editor.start = function (userSettings) {

        init();

        editor.core.prepare(userSettings)

        // If all ok, make UI, bind events and parse initial-content
            .then(editor.ui.make)
            .then(editor.ui.addTools)
            .then(editor.ui.bindEvents)
            .then(editor.ui.preparePlugins)
            .then(editor.transport.prepare)
            .then(editor.renderer.makeBlocksFromData)
            .then(editor.ui.saveInputs)
            .catch(function (error) {

                editor.core.log('Initialization failed with error: %o', 'warn', error);

            });

    };

    return editor;

})({});