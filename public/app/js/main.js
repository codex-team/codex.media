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

    codex.init = function () {

        /**
        * Stylize custom checkboxes
        */
        codex.content.customCheckboxes.init();

        /**
        * Init approval buttons
        */
        codex.content.approvalButtons.init();

        codex.autoresizeTextarea.init();

        codex.dropdownMenu({
            holderClass: 'js-dropdown-menu--holder',
            menuClass: 'js-dropdown-menu--menu',
            menuCSSClass: 'dropdown-menu',
            menuClosedClass: 'dropdown-menu--closed',
            menuOptions: {

                'Открыть Google': function () {

                    window.location.assign('https://google.com');

                },
                'Открыть Яндекс': function () {

                    window.location.assign('https://yandex.ru');

                },
                'Открыть ВКонтакте': function () {

                    window.location.assign('https://vk.com');

                },
                'Очень-очень длинное (нет) название варианта': function () {

                    window.alert('Всем сохранять спокойствие, работает колбэк');

                }
            }
        });

        /**
        * Init CodeX Special module for contrast version
        * @see https://github.com/codex-team/codex.special
        */
        window.codexSpecial.init({
            blockId : 'js-contrast-version-holder',
        });

        codex.core.log('Initialized', 'App init', 'info');

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
codex.dropdownMenu       = require('./modules/dropdownMenu');
codex.autoresizeTextarea = require('./modules/autoresizeTextarea');
codex.profileSettings    = require('./modules/profileSettings');
codex.sharer             = require('./modules/sharer');

module.exports = codex;

codex.docReady(function () {

    codex.init();

});
