/**
 * Import Dispatcher for Frontend Modules initialization
 */
import moduleDispatcher from 'module-dispatcher';

/**
 * Codex client
 * @author Savchenko Peter <specc.dev@gmail.com>
 */
var codex = {};

codex = function () {

    'use strict';

    /**
     * Static nodes cache
     */

    codex.nodes = {
        content: null
    };

    /**
     * @var Application settings
     * @type {Object}
     * @type {Number} appSettings.uploadMaxSize    - max size for Editor uploads in MB
     */
    codex.appSettings = {
        uploadMaxSize: 25
    };

    /**
     * Initiztes application
     * @param {Object} appSettings - initial settings
     */
    codex.init = function (appSettings) {

        /**
         * Accept settings
         */
        for (var key in appSettings) {

            codex.appSettings[key] = appSettings[key];

        }

        codex.docReady(function () {

            initModules();

        });


    };

    /**
     * Function responsible for modules initialization
     * Called no earlier than document is ready
     */
    function initModules() {

        /**
         * Instantiate moduleDispatcher to init Modules
         * @type {moduleDispatcher}
         */
        new moduleDispatcher({
            Library : codex
        });

        /**
         * CodeX Special
         *
         * Availiable options:
         *    position {String} (optional) — toolbar position on screen
         *        'top-left', 'bottom-right', 'bottom-left', 'top-right'
         *    blockId {String} (optional) — toolbar wrapper
         *    lang {String} (optional) — language 'ru' or 'en'. (default: 'ru')
         */
        codex.special.init({
            blockId: 'js-contrast-version-holder'
        });

        /**
         * Stylize custom checkboxes
         */
        codex.checkboxes.init();

        /**
         * Init approval buttons
         */
        codex.content.approvalButtons.init();

        /**
         * Enable textarea autoresizer
         */
        codex.autoresizeTextarea.init();

        /**
         * Activate scroll-up button
         */
        codex.scrollUp.init('js-layout-holder');

        /**
         * Client is ready
         */
        codex.core.log('Initialized', 'CodeX', 'info');

        /**
         * Initiate branding preload
         */
        codex.branding.init();

    }

    return codex;

}();

/**
 * Document ready handler
 */
codex.docReady = function (f) {

    /in/.test(document.readyState) ? window.setTimeout(codex.docReady, 9, f) : f();

};

/**
 * Load modules
 */
codex.core = require('./modules/core');
codex.ajax = require('./modules/ajax');
codex.transport = require('./modules/transport');
codex.content = require('./modules/content');
codex.appender = require('./modules/appender');
codex.parser = require('./modules/parser');
codex.comments = require('./modules/comments');
codex.alerts = require('./modules/alerts');
codex.islandSettings = require('./modules/islandSettings');
codex.autoresizeTextarea = require('./modules/autoresizeTextarea');
codex.user = require('./modules/user');
codex.sharer = require('./modules/sharer');
codex.writing = require('./modules/writing');
codex.loader = require('./modules/loader');
codex.scrollUp = require('./modules/scrollUp');
codex.branding = require('./modules/branding');
codex.pages = require('./modules/pages');
codex.checkboxes = require('./modules/checkboxes');
codex.logo = require('./modules/logo');
codex.special = require('codex.special');
codex.avatarUploader = require('./modules/avatarUploader');
codex.layout = require('./modules/layout');
codex.pageTypeSelector = require('./modules/pageTypeSelector');
codex.datePicker = require('./modules/datePicker');

const SearchModule = require('./modules/search').default;

codex.search = new SearchModule();

module.exports = codex;
