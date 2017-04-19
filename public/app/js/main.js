/**
* Require CSS build
*/
require('../css/main.css');

/**
* Codex client
* @author Savchenko Peter <specc.dev@gmail.com>
*/
codex = (function (codex) {

    'use strict';

    /**
    * Static nodes cache
    */
    codex.nodes = {
        content : null
    };

    /**
     * @var Application settings
     * @type {Object}
     * @type {Number} appSettings.uploadMaxSize    - max size for Editor uploads in MB
     */
    codex.appSettings = {
        uploadMaxSize : 25
    };

    /**
     * Initiztes application
     * @param {Object} appSettings - initial settings
     */
    codex.init = function ( appSettings ) {

        /**
         * Accept settings
         */
        for ( var key in appSettings ) {

            codex.appSettings[key] = appSettings[key];

        }

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

    };

    return codex;

})({});

/**
* Document ready handler
*/
codex.docReady = function (f) {

    /in/.test(document.readyState) ? window.setTimeout(codex.docReady, 9, f) : f();

};


/**
* Load modules
*/
codex.core               = require('./modules/core');
codex.ajax               = require('./modules/ajax');
codex.transport          = require('./modules/transport');
codex.content            = require('./modules/content');
codex.appender           = require('./modules/appender');
codex.parser             = require('./modules/parser');
codex.comments           = require('./modules/comments');
codex.alerts             = require('./modules/alerts');
codex.islandSettings     = require('./modules/islandSettings');
codex.autoresizeTextarea = require('./modules/autoresizeTextarea');
codex.user               = require('./modules/user');
codex.sharer             = require('./modules/sharer');
codex.writing            = require('./modules/writing');
codex.loader             = require('./modules/loader');
codex.scrollUp           = require('./modules/scrollUp');
codex.branding           = require('./modules/branding');
codex.pages              = require('./modules/pages');
codex.checkboxes         = require('./modules/checkboxes');


module.exports = codex;
