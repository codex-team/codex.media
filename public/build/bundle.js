var codex =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _moduleDispatcher = __webpack_require__(1);

var _moduleDispatcher2 = _interopRequireDefault(_moduleDispatcher);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * Require CSS build
 */
__webpack_require__(2);

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
     * Initiate modules
     * @type {moduleDispatcher}
     */
    new _moduleDispatcher2.default({
      Library: codex
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

    /**
     * Set listener for mobile menu toggler
     */
    codex.content.setMobileMenuToggler('js-mobile-menu-toggler');
  };

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
codex.core = __webpack_require__(3);
codex.ajax = __webpack_require__(4);
codex.transport = __webpack_require__(5);
codex.content = __webpack_require__(6);
codex.appender = __webpack_require__(7);
codex.parser = __webpack_require__(8);
codex.comments = __webpack_require__(9);
codex.alerts = __webpack_require__(10);
codex.islandSettings = __webpack_require__(12);
codex.autoresizeTextarea = __webpack_require__(13);
codex.user = __webpack_require__(14);
codex.sharer = __webpack_require__(15);
codex.writing = __webpack_require__(16);
codex.loader = __webpack_require__(19);
codex.scrollUp = __webpack_require__(20);
codex.branding = __webpack_require__(21);
codex.pages = __webpack_require__(22);
codex.checkboxes = __webpack_require__(25);
codex.logo = __webpack_require__(26);
codex.special = __webpack_require__(27);

module.exports = codex;

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Module Dispatcher
 * Class for Modules initialization
 *
 * @copyright CodeX Team
 * @license MIT/GPL
 * @author @polinashneider
 *
 * @version 1.0.0
 *
 * @example
 *
 * new moduleDispatcher({
 *   Library : codex
 * });
 */

/**
 * Module object structure:
 *
 * @typedef {Module} Module
 * @property {String} name          - Module's name
 * @property {Element} element      - DOM Element with data-module
 * @property {Object|null} settings - Module settings got from <module-settings>
 * @property {Object} moduleClass   - JS class that handles the Module
 */

var Module = function () {
    function Module(_ref) {
        var name = _ref.name,
            element = _ref.element,
            settings = _ref.settings,
            moduleClass = _ref.moduleClass;

        _classCallCheck(this, Module);

        this.name = name;
        this.element = element;
        this.settings = settings;
        this.moduleClass = moduleClass;
    }

    /**
     * Initialize each Module by calling its own «init» method
     */


    _createClass(Module, [{
        key: 'init',
        value: function init() {

            try {

                console.assert(this.moduleClass.init instanceof Function, 'Module «' + this.name + '» should implement init method');

                if (this.moduleClass.init instanceof Function) {

                    this.moduleClass.init(this.settings, this.element);
                    console.log('Module \xAB' + this.name + '\xBB initialized');
                }
            } catch (e) {

                console.warn('Module «' + this.name + '» was not initialized because of ', e);
            }
        }

        /**
         * Destroy each Module by calling its own «destroy» method, if it exists
         * It is optional
         */

    }, {
        key: 'destroy',
        value: function destroy() {

            if (this.moduleClass.destroy instanceof Function) {

                this.moduleClass.destroy();
                console.log('Module \xAB' + this.name + '\xBB destroyed.');
            }
        }
    }]);

    return Module;
}();

/**
 * Class structure
 *
 * @typedef {moduleDispatcher} moduleDispatcher
 * @property {Object} Library    - global object, containing Modules to init
 * @property {Module[]} modules  - list of Modules
*/


var moduleDispatcher = function () {

    /**
     * @param {Object|null} settings — settings object, optional
     * @param {Object} settings.Library — global object containing Modules
     */
    function moduleDispatcher(settings) {
        _classCallCheck(this, moduleDispatcher);

        this.Library = settings.Library || window;

        /**
         * Found modules list
         * @type {Module[]}
         */
        this.modules = this.findModules(document);

        /**
         * Now we are ready to init Modules
         */
        this.initModules();
    }

    /**
     * Return all modules inside the passed Element
     *
     * @param {Element} element — where to find modules
     * @return {Module[]} found modules list
     */


    _createClass(moduleDispatcher, [{
        key: 'findModules',
        value: function findModules(element) {

            /**
             * Store found modules
             * @type {Module[]}
             */
            var modules = [];

            /**
             * Elements with data-module
             * @type {NodeList}
             */
            var elements = element.querySelectorAll('[data-module]');

            /**
             * Iterate found Elements and push them to the Modules list
             */
            for (var i = elements.length - 1; i >= 0; i--) {

                /**
                 * One Element can contain several Modules
                 * @type {Array}
                 */
                modules.push.apply(modules, _toConsumableArray(this.extractModulesData(elements[i])));
            }

            return modules;
        }

        /**
         * Get all modules from an Element
         *
         * @example <div data-module="comments likes">
         * @return {Module[]} - Array of Module objects with settings
         */

    }, {
        key: 'extractModulesData',
        value: function extractModulesData(element) {
            var _this = this;

            var modules = [];
            /**
             * Get value of data-module attribute
             */
            var modulesList = element.dataset.module;
            /**
             * In case of multiple spaces in modulesList replace with single ones
             */

            modulesList = modulesList.replace(/\s+/, ' ');

            /**
             * One Element can contain several modules
             * @example <div data-module="comments likes">
             * @type {Array}
             */
            var moduleNames = modulesList.split(' ');

            moduleNames.forEach(function (name, index) {

                var module = new Module({
                    name: name,
                    element: element,
                    settings: _this.getModuleSettings(element, index, name),
                    moduleClass: _this.Library[name]
                });

                modules.push(module);
            });

            return modules;
        }

        /**
         * Returns Settings for the Module
         *
         * @param {object} element — HTML element with data-module attribute
         * @param {Number} index   - index of module (in case if an Element countains several modules)
         * @param {String} name    - Module's name
         *
         * @example
         *
         * <module-settings hidden>
         *     {
         *         // your module's settings
         *     }
         * </module-settings>
         *
         */

    }, {
        key: 'getModuleSettings',
        value: function getModuleSettings(element, index, name) {

            var settingsNodes = element.querySelector('module-settings'),
                settingsObject = void 0;

            if (!settingsNodes) {

                return null;
            }

            try {

                settingsObject = settingsNodes.textContent.trim();
                settingsObject = JSON.parse(settingsObject);
            } catch (e) {

                console.warn('Can not parse Module \xAB' + name + '\xBB settings bacause of: ' + e);
                console.groupCollapsed(name + ' settings');
                console.log(settingsObject);
                console.groupEnd();

                return null;
            }

            /**
             * Case 1:
             *
             * Single module, settings via object
             *
             * <module-settings>
             *     {
             *         // Comments Module settings
             *     }
             * </module-settings>
             */
            if (!Array.isArray(settingsObject)) {

                if (index === 0) {

                    return settingsObject;
                } else {

                    console.warn('Wrong settings format. For several Modules use an array instead of object.');
                    return null;
                }
            }

            /**
             * Case 2:
             *
             * Several modules, settings via array
             *
             * <module-settings>
             *   [
             *     {
             *         // Module 1 settings
             *     },
             *     {
             *         // Module 2 settings
             *     },
             *     ...
             *   ]
             * </module-settings>
             */
            if (settingsObject[index]) {

                return settingsObject[index];
            } else {

                return null;
            }
        }

        /**
         * Initializes a list of Modules via calling {@link Module#init} for each
         */

    }, {
        key: 'initModules',
        value: function initModules() {

            console.groupCollapsed('ModuleDispatcher');

            this.modules.forEach(function (module) {

                module.init();
            });

            console.groupEnd();
        }
    }]);

    return moduleDispatcher;
}();

exports.default = moduleDispatcher;
;

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/**
* Significant core methods
*/

module.exports = {

    /** Logging method */
    log: function log(str, prefix, type, arg) {

        var staticLength = 32;

        if (prefix) {

            prefix = prefix.length < staticLength ? prefix : prefix.substr(0, staticLength - 2);

            while (prefix.length < staticLength - 1) {

                prefix += ' ';
            }

            prefix += ':';
            str = prefix + str;
        }

        type = type || 'log';

        try {

            if ('console' in window && window.console[type]) {

                if (arg) console[type](str, arg);else console[type](str);
            }
        } catch (e) {}
    },

    /**
    * @return {object} dom element real offset
    */
    getOffset: function getOffset(elem) {

        var docElem, win, rect, doc;

        if (!elem) {

            return;
        }

        /**
        * Support: IE <=11 only
        * Running getBoundingClientRect on a
        * disconnected node in IE throws an error
        */
        if (!elem.getClientRects().length) {

            return {
                top: 0,
                left: 0
            };
        }

        rect = elem.getBoundingClientRect();

        /** Make sure element is not hidden (display: none) */
        if (rect.width || rect.height) {

            doc = elem.ownerDocument;
            win = window;
            docElem = doc.documentElement;

            return {
                top: rect.top + win.pageYOffset - docElem.clientTop,
                left: rect.left + win.pageXOffset - docElem.clientLeft
            };
        }

        /** Return zeros for disconnected and hidden elements (gh-2310) */
        return rect;
    },

    /**
    * Checks if element visible on screen at the moment
    * @param {Element} - HTML NodeElement
    */
    isElementOnScreen: function isElementOnScreen(el) {

        var elPositon = codex.core.getOffset(el).top,
            screenBottom = window.scrollY + window.innerHeight;

        return screenBottom > elPositon;
    },

    /**
    * Returns computed css styles for element
    * @param {Element} el
    */
    css: function css(el) {

        return window.getComputedStyle(el);
    },

    /**
    * Helper for inserting one element after another
    */
    insertAfter: function insertAfter(target, element) {

        target.parentNode.insertBefore(element, target.nextSibling);
    },

    /**
    * Replaces node with
    * @param {Element} nodeToReplace
    * @param {Element} replaceWith
    */
    replace: function replace(nodeToReplace, replaceWith) {

        return nodeToReplace.parentNode.replaceChild(replaceWith, nodeToReplace);
    },

    /**
    * Helper for insert one element before another
    */
    insertBefore: function insertBefore(target, element) {

        target.parentNode.insertBefore(element, target);
    },

    /**
    * Returns random {int} between numbers
    */
    random: function random(min, max) {

        return Math.floor(Math.random() * (max - min + 1)) + min;
    },

    /**
    * Attach event to Element in parent
    * @param {Element} parentNode    - Element that holds event
    * @param {string} targetSelector - selector to filter target
    * @param {string} eventName      - name of event
    * @param {function} callback     - callback function
    */
    delegateEvent: function delegateEvent(parentNode, targetSelector, eventName, callback) {

        parentNode.addEventListener(eventName, function (event) {

            var el = event.target,
                matched;

            while (el && !matched) {

                matched = el.matches(targetSelector);

                if (!matched) el = el.parentElement;
            }

            if (matched) {

                callback.call(event.target, event, el);
            }
        }, true);
    },

    /**
    * Readable DOM-node types map
    */
    nodeTypes: {
        TAG: 1,
        TEXT: 3,
        COMMENT: 8,
        DOCUMENT_FRAGMENT: 11
    },

    /**
    * Readable keys map
    */
    keys: { BACKSPACE: 8, TAB: 9, ENTER: 13, SHIFT: 16, CTRL: 17, ALT: 18, ESC: 27, SPACE: 32, LEFT: 37, UP: 38, DOWN: 40, RIGHT: 39, DELETE: 46, META: 91 },

    /**
    * @protected
    * Check object for DOM node
    */
    isDomNode: function isDomNode(el) {

        return el && (typeof el === 'undefined' ? 'undefined' : _typeof(el)) === 'object' && el.nodeType && el.nodeType == this.nodeTypes.TAG;
    },

    /**
    * Parses string to nodeList
    * Removes empty text nodes
    * @param {string} inputString
    * @return {array} of nodes
    *
    * Does not supports <tr> and <td> on firts level of inputString
    */

    parseHTML: function parseHTML(inputString) {

        // var templatesSupported = spark.supports.templates();

        var contentHolder,
            childs,
            parsedNodes = [];

        // if ( false &&   templatesSupported ) {

        //     contentHolder = document.createElement('template');
        //     contentHolder.innerHTML = inputString.trim();

        //     console.log("contentHolder: %o", contentHolder);

        //     childs = contentHolder.content.cloneNode(true).childNodes;

        // } else {

        contentHolder = document.createElement('div');
        contentHolder.innerHTML = inputString.trim();

        childs = contentHolder.childNodes;

        // }


        /**
        * Iterate childNodes and remove empty Text Nodes on first-level
        */
        for (var i = 0, node; !!(node = childs[i]); i++) {

            if (node.nodeType == codex.core.nodeTypes.TEXT && !node.textContent.trim()) {

                continue;
            }

            parsedNodes.push(node);
        }

        return parsedNodes;
    },

    /**
    * Checks passed object for emptiness
    * @require ES5 - Object.keys
    * @param {object}
    */
    isEmpty: function isEmpty(obj) {

        return Object.keys(obj).length === 0;
    },

    /**
    * Check for Element visibility
    * @param {Element} el
    */
    isVisible: function isVisible(el) {

        return el.offsetParent !== null;
    },

    setCookie: function setCookie(name, value, expires, path, domain) {

        var str = name + '=' + value;

        if (expires) str += '; expires=' + expires.toGMTString();
        if (path) str += '; path=' + path;
        if (domain) str += '; domain=' + domain;

        document.cookie = str;
    },

    getCookie: function getCookie(name) {

        var dc = document.cookie;

        var prefix = name + '=',
            begin = dc.indexOf('; ' + prefix);

        if (begin == -1) {

            begin = dc.indexOf(prefix);
            if (begin !== 0) return null;
        } else begin += 2;

        var end = document.cookie.indexOf(';', begin);

        if (end == -1) end = dc.length;

        return unescape(dc.substring(begin + prefix.length, end));
    }

};

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
* AJAX module
*/
var ajax = function () {

    /**
    * @usage codex.ajax.call();
    */
    var call = function call(data) {

        if (!data || !data.url) return;

        var XMLHTTP = window.XMLHttpRequest ? new window.XMLHttpRequest() : new window.ActiveXObject('Microsoft.XMLHTTP'),
            successFunction = function successFunction() {};

        data.async = true;
        data.type = data.type || 'GET';
        data.data = data.data || '';
        data['content-type'] = data['content-type'] || 'application/json; charset=utf-8';
        successFunction = data.success || successFunction;

        if (data.type == 'GET' && data.data) {

            data.url = /\?/.test(data.url) ? data.url + '&' + data.data : data.url + '?' + data.data;
        }

        if (data.withCredentials) {

            XMLHTTP.withCredentials = true;
        }

        if (data.beforeSend && typeof data.beforeSend == 'function') {

            data.beforeSend.call();
        }

        XMLHTTP.open(data.type, data.url, data.async);

        /**
        * If we send FormData, we need no content-type header
        */
        if (!isFormData(data.data)) {

            XMLHTTP.setRequestHeader('Content-type', data['content-type']);
        }

        XMLHTTP.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        XMLHTTP.onreadystatechange = function () {

            if (XMLHTTP.readyState == 4 && XMLHTTP.status == 200) {

                successFunction(XMLHTTP.responseText);
            }
        };

        XMLHTTP.send(data.data);
    };

    /**
     * Function for checking is it FormData object to send.
     * @param {Object} object to check
     * @return boolean
     */
    function isFormData(object) {

        return typeof object.append === 'function';
    };

    return {

        call: call

    };
}();

module.exports = ajax;

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/**
 * File transport module
 *
 * @module Transport module. Uploads file and returns some response from server
 * @copyright Codex-Team 2017
 *
 * @example
 *
 * Basic usage :
 *  codex.transport.init( {
 *      url : fetchURL,
 *      multiple : bool,
 *      accept : string  // http://htmlbook.ru/html/input/accept
 *      beforeSend : Function,
 *      success : Function,
 *      error : Function
 *      data : Object — additional data
 * });
 *
 * You can handle all of this event like:
 *  - what should happen before data sending with XMLHTTP
 *  - what should after success request
 *  - error handler
 */

module.exports = function (transport) {

    /** Empty configuration */
    var config_ = null;

    /** File holder */
    transport.input = null;

    /** initialize module */
    transport.init = function (configuration) {

        if (!configuration.url) {

            codex.core.log('can\'t send request because `url` is missed', 'Transport module', 'error');
            return;
        }

        config_ = configuration;

        var inputElement = document.createElement('INPUT');

        inputElement.type = 'file';

        if (config_ && config_.multiple) {

            inputElement.setAttribute('multiple', 'multiple');
        }

        if (config_ && config_.accept) {

            inputElement.setAttribute('accept', config_.accept);
        }

        inputElement.addEventListener('change', send_, false);

        /** Save input */
        transport.input = inputElement;

        /** click input to show upload window */
        clickInput_();
    };

    var clickInput_ = function clickInput_() {

        transport.input.click();
    };

    /**
     * Sends transport AJAX request
     */
    var send_ = function send_() {

        var url = config_.url,
            beforeSend = config_.beforeSend,
            success = config_.success,
            error = config_.error,
            formData = new FormData(),
            files = transport.input.files;

        if (files.length > 1) {

            for (var i = 0; i < files.length; i++) {

                formData.append('files[]', files[i], files[i].name);
            }
        } else {

            formData.append('files', files[0], files[0].name);
        }

        /**
         * Append additional data
         */
        if (config_.data !== null && _typeof(config_.data) === 'object') {

            for (var key in config_.data) {

                formData.append(key, config_.data[key]);
            }
        }

        codex.ajax.call({
            type: 'POST',
            data: formData,
            url: url,
            beforeSend: beforeSend,
            success: success,
            error: error
        });
    };

    return transport;
}({});

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
* Operations with pages
*/
module.exports = function () {

    /**
    * Toggles classname on passed blocks
    * @param {string} selector
    * @param {string} toggled classname
    */
    var toggle = function toggle(which, marker) {

        var elements = document.querySelectorAll(which);

        for (var i = elements.length - 1; i >= 0; i--) {

            elements[i].classList.toggle(marker);
        }
    };

    /**
    * Toggles mobile menu
    * Handles clicks on the hamburger icon in header
    */
    var toggleMobileMenu = function toggleMobileMenu(event) {

        var menu = document.getElementById('js-mobile-menu-holder'),
            openedClass = 'mobile-menu-holder--opened';

        menu.classList.toggle(openedClass);

        event.stopPropagation();
        event.stopImmediatePropagation();
        event.preventDefault();
    };

    /**
     * Create listener for mobile menu toggler
     * @param {String} id — toggler's id
     */
    var setMobileMenuToggler = function setMobileMenuToggler(id) {

        var menuToggler = document.getElementById(id);

        if (!menuToggler) {

            return;
        }

        menuToggler.addEventListener('click', toggleMobileMenu);
    };

    /**
    * Module uses for toggle custom checkboxes
    * that has 'js-custom-checkbox' class and input[type="checkbox"] included
    * Example:
    * <span class="js-custom-checkbox">
    *    <input type="checkbox" name="" value="1"/>
    * </span>
    */
    var customCheckboxes = {

        /**
        * This class specifies checked custom-checkbox
        * You may set it on serverisde
        */
        CHECKED_CLASS: 'checked',

        init: function init() {

            var checkboxes = document.getElementsByClassName('js-custom-checkbox');

            if (checkboxes.length) for (var i = checkboxes.length - 1; i >= 0; i--) {

                checkboxes[i].addEventListener('click', codex.content.customCheckboxes.clicked, false);
            }
        },

        clicked: function clicked() {

            var checkbox = this,
                input = this.querySelector('input'),
                isChecked = this.classList.contains(codex.content.customCheckboxes.CHECKED_CLASS);

            checkbox.classList.toggle(codex.content.customCheckboxes.CHECKED_CLASS);

            if (isChecked) {

                input.removeAttribute('checked');
            } else {

                input.setAttribute('checked', 'checked');
            }
        }
    };

    var approvalButtons = {

        CLICKED_CLASS: 'click-again-to-approve',

        init: function init() {

            var buttons = document.getElementsByClassName('js-approval-button');

            if (buttons.length) for (var i = buttons.length - 1; i >= 0; i--) {

                buttons[i].addEventListener('click', codex.content.approvalButtons.clicked, false);
            }
        },

        clicked: function clicked(event) {

            var button = this,
                isClicked = this.classList.contains(codex.content.approvalButtons.CLICKED_CLASS);

            if (!isClicked) {

                /* временное решение, пока нет всплывающего окна подверждения важных действий */
                button.classList.add(codex.content.approvalButtons.CLICKED_CLASS);

                event.preventDefault();
            }
        }
    };

    return {

        toggleMobileMenu: toggleMobileMenu,
        setMobileMenuToggler: setMobileMenuToggler,
        customCheckboxes: customCheckboxes,
        approvalButtons: approvalButtons,
        toggle: toggle

    };
}();

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Appender is being used for ajax-loading next pages of lists
 *
 *    codex.appender.init({
 *        buttonId      : 'buttonLoadNews',       // button for listening
 *        currentPage   : '<?= $page_number ?>',    // currentPage number
 *        url           : '/',                      // url for ajax-requests
 *        targetBlockId : 'list_of_news',           // target for appending
 *        autoLoading   : true,                     // allow loading when reach bottom while scrolling
 *    });
 */

var appender = {

    /* Pagination. Here is a number of current page */
    page: 1,

    settings: null,

    blockForItems: null,

    loadMoreButton: null,

    /**
     * Button's text for saving it.
     * On its place dots will be while news are loading
     *
     * @param {Object} button — button loading more news,
     *                          passed from moduleDispatcher
     */
    buttonText: null,

    init: function init(settings, button) {

        this.settings = settings;

        /* Checking for existing button and field for loaded info */
        this.loadMoreButton = button;

        if (!this.loadMoreButton) return false;

        this.blockForItems = document.getElementById(this.settings.targetBlockId);

        if (!this.blockForItems) return false;

        this.page = settings.currentPage;
        this.buttonText = this.loadMoreButton.innerHTML;

        if (this.settings.autoLoading) this.autoLoading.isAllowed = true;

        this.loadMoreButton.addEventListener('click', function (event) {

            codex.appender.load();

            event.preventDefault();

            codex.appender.autoLoading.init();
        }, false);

        /** Force init if need no waiting for a first click */
        if (this.settings.dontWaitFirstClick) {

            codex.appender.autoLoading.init();
        }
    },

    load: function load() {

        var requestUrl = this.settings.url + (parseInt(this.page) + 1);
        // separator   = '<a href="' + requestUrl + '"><div class="article post-list-item w_island separator">Page ' + (parseInt(this.page) + 1) + '</div></a>';

        codex.ajax.call({
            type: 'post',
            url: requestUrl,
            data: {},
            beforeSend: function beforeSend() {

                codex.appender.loadMoreButton.classList.add('loading');
            },
            success: function success(response) {

                response = JSON.parse(response);

                if (response.success) {

                    if (!response.list) return;

                    /* Append items */
                    // codex.appender.blockForItems.innerHTML += separator;
                    codex.appender.blockForItems.innerHTML += response.list;

                    /* Next page */
                    codex.appender.page++;

                    if (codex.appender.settings.autoLoading) {

                        /* Removing restriction for auto loading */
                        codex.appender.autoLoading.canLoad = true;
                    }

                    /* Checking for next page's existing. If no — hide the button for loading news and remove listener */
                    if (!response.next_page) codex.appender.disable();
                } else {

                    codex.core.showException('Не удалось подгрузить новости');
                }

                codex.appender.loadMoreButton.classList.remove('loading');
            }

        });
    },

    disable: function disable() {

        codex.appender.loadMoreButton.style.display = 'none';

        if (codex.appender.autoLoading.isLaunched) {

            codex.appender.autoLoading.disable();
        }
    },

    autoLoading: {

        isAllowed: false,

        isLaunched: false,

        /**
         * Possibility to load news by scrolling.
         * Restriction for reduction requests which could be while scrolling
         */
        canLoad: true,

        init: function init() {

            if (!this.isAllowed) return;

            window.addEventListener('scroll', codex.appender.autoLoading.scrollEvent);

            codex.appender.autoLoading.isLaunched = true;
        },

        disable: function disable() {

            window.removeEventListener('scroll', codex.appender.autoLoading.scrollEvent);

            codex.appender.autoLoading.isLaunched = false;
        },

        scrollEvent: function scrollEvent() {

            var scrollReachedEnd = window.pageYOffset + window.innerHeight >= document.body.clientHeight;

            if (scrollReachedEnd && codex.appender.autoLoading.canLoad) {

                codex.appender.autoLoading.canLoad = false;

                codex.appender.load();
            }
        }

    }

};

module.exports = appender;

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Parser module
 * @author Taly Guryn
 */
var parser = {

    input: null,

    init: function init() {

        // this.input = document.getElementById(settings.input_id);

        var _this = this;

        this.input.addEventListener('paste', function () {

            _this.inputPasteCallback();
        }, false);
    },

    inputPasteCallback: function inputPasteCallback() {

        var e = this.input;

        var _this = this;

        window.setTimeout(function () {

            _this.sendRequest(e.value);
        }, 100);
    },

    sendRequest: function sendRequest(url) {

        codex.core.ajax({
            type: 'get',
            url: '/ajax/get_page',
            data: { 'url': url },
            success: function success(response) {

                var title, content, sourceLink;

                if (response.success == 1) {

                    title = document.getElementById('page_form_title');
                    content = document.getElementById('page_form_content');
                    sourceLink = document.getElementById('source_link');

                    title.value = response.title;
                    content.value = response.article;
                    sourceLink.value = url;

                    // while we have no own editor, we should use this getting element
                    // cause I can't edit code for external editor
                    document.getElementsByClassName('redactor_redactor')[0].innerHTML = response.article;
                } else {

                    codex.core.showException('Не удалось импортировать страницу');
                }
            }

        });
    }
};

module.exports = parser;

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Comments module
 * @author  @guryn @neSpecc
 * @copyright CodeX Team https://github.com/codex-team
 * @version 1.1.0
 */
module.exports = function () {

    var commentsList = null,
        anchor = document.location.hash;

    var CSS_ = {
        replyForm: 'comments-form',
        replyTextarea: 'comment-form__text',
        replyOpened: 'comment-form__placeholder--opened',
        replySubmitButton: 'comment-form__button',
        highlighted: 'comment--highligthed'
    };

    /**
     * Settings-menu toggler selector
     * @type {String}
     */
    var menuTogglerSelector = '.js-comment-settings';

    /**
     * Initialize comments
     * @param {Object} data params
     * @param {Object} list — list of comments,
     *                        passed from moduleDispatcher
     */
    function init(data, list) {

        commentsList = list;

        if (anchor) {

            highligthAnchor();
        }
    }

    /**
     * Remove holder and append form for comment
     * @param {Element} placeholder 'Write reply...' button
     */
    function reply(replyButton) {

        /** If reply already opened, do noting */
        if (replyButton.classList.contains(CSS_.replyOpened)) {

            return;
        }

        /** Get reply params from dataset */
        var replyParams = {
            parentId: replyButton.dataset.parentId,
            rootId: replyButton.dataset.rootId,
            action: replyButton.dataset.action
        };

        /** Create reply form */
        var form = createForm(replyParams);

        /** Insert form after reply button */
        codex.core.insertAfter(replyButton, form);

        replyButton.classList.add(CSS_.replyOpened);
        getFormTextarea(form).focus();
    }

    /**
     * Returns reply form
     *
     * @param {object} params
     * @param {Number} params.parentId     parent comment's id
     * @param {Number} params.rootId       root comment's id
     * @param {String} params.action       URL for saving
     *
     * @return {Element} element that holds textarea and submit-button
     */
    function createForm(params) {

        var textarea = createTextarea(),
            button = createButton(),
            form = document.createElement('DIV');

        form.classList.add(CSS_.replyForm);

        /** Store data in Textarea */
        textarea.dataset.parentId = params.parentId;
        textarea.dataset.rootId = params.rootId;
        textarea.dataset.action = params.action;

        form.appendChild(textarea);
        form.appendChild(button);

        return form;
    }

    /** Return textarea for form for comment */
    function createTextarea() {

        var textarea = document.createElement('TEXTAREA');

        textarea.classList.add(CSS_.replyTextarea);
        textarea.placeholder = 'Ваш комментарий';

        textarea.addEventListener('keydown', keydownSubmitHandler, false);
        textarea.addEventListener('blur', blurTextareaHandler, false);

        codex.autoresizeTextarea.addListener(textarea);

        return textarea;
    }

    /** Return submit button for form*/
    function createButton() {

        var button = document.createElement('DIV');

        button.classList.add(CSS_.replySubmitButton, 'button', 'master');
        button.textContent = 'Отправить';

        button.addEventListener('click', submitClicked_, false);

        return button;
    }

    /**
     * Reply submit button click handler
     */
    function submitClicked_() {

        var submit = this,
            form = submit.parentNode,
            textarea = getFormTextarea(form);

        send_(textarea);
    }

    /* Return textarea for given form */
    function getFormTextarea(form) {

        return form.getElementsByTagName('TEXTAREA')[0];
    }

    /**
     * Remove form on textarea blur
     * @param {Event} blur Event
     */
    function blurTextareaHandler(event) {

        var textarea = event.target,
            form = textarea.parentNode,
            commentId = textarea.dataset.parentId;

        if (!textarea.value.trim()) {

            removeForm(form, commentId);
        }
    }

    /**
     * Removes reply form
     * @param {Element} form
     * @param {Number} commentId   reply target comment id
     */
    function removeForm(form, commentId) {

        var replyButton = document.getElementById('reply' + commentId);

        form.remove();
        replyButton.classList.remove(CSS_.replyOpened);
    }

    /**
     * Catch Ctrl+Enter or Cmd+Enter for send form
     * @param {Event} event    Keydown Event
     */
    function keydownSubmitHandler(event) {

        var ctrlPressed = event.ctrlKey || event.metaKey,
            enterPressed = event.keyCode == 13,
            textarea = event.target;

        if (ctrlPressed && enterPressed) {

            send_(textarea);

            event.preventDefault();
        }
    }

    /**
     * Ajax function for submit comment
     * @param {Element} textarea    input with dataset and text
     */
    function send_(textarea) {

        var formData = new FormData(),
            form = textarea.parentNode,
            submitBtn = form.querySelector('.' + CSS_.replySubmitButton),
            rootId = textarea.dataset.rootId,
            parentId = textarea.dataset.parentId,
            actionURL = textarea.dataset.action;

        formData.append('root_id', rootId);
        formData.append('parent_id', parentId);
        formData.append('comment_text', textarea.value);
        formData.append('csrf', window.csrf);

        codex.ajax.call({
            type: 'POST',
            url: actionURL,
            data: formData,
            beforeSend: function beforeSend() {

                submitBtn.classList.add('loading');
            },
            success: function success(response) {

                var comment;

                submitBtn.classList.remove('loading');

                response = JSON.parse(response);

                if (!response.success) {

                    codex.alerts.show({
                        type: 'error',
                        message: response.error
                    });
                    return;
                }

                /** Remove form and return placeholder */
                removeForm(form, parentId);

                /** Remove empty-feed block */
                removeEmptyCommentsBlock();

                comment = codex.core.parseHTML(response.comment)[0];
                commentsList.appendChild(comment);

                /** Scroll down to the new comment */
                window.scrollTo(0, document.body.scrollHeight);

                /** Highligth new comment */
                highligthComment(response.commentId);

                /** If menu found, activate it */
                activateMenu(comment);
            }

        });
    }

    /**
     * Removes empty-feed motivation
     */
    function removeEmptyCommentsBlock() {

        var emptyCommentsBlock = document.querySelector('.js-empty-comments');

        if (!emptyCommentsBlock) {

            return;
        }

        emptyCommentsBlock.remove();
    }

    /**
     * If menu-toggler found in comment
     * @return {Element} comment - comment's island
     */
    function activateMenu(comment) {

        var menuToggler = comment.querySelector(menuTogglerSelector);

        if (!menuToggler) {

            return;
        }

        codex.islandSettings.prepareToggler(menuToggler, menuTogglerSelector);
    }

    /**
     * Highligth comment by id for a time
     * @param {Number} commentId   id comment to highlight
     */
    function highligthComment(commentId) {

        var comment = document.getElementById('comment' + commentId);

        comment.classList.add(CSS_.highlighted);

        window.setTimeout(function () {

            comment.classList.remove(CSS_.highlighted);
        }, 500);
    }

    /** Highligth comment if anchor is in url */
    function highligthAnchor() {

        var numbers = anchor.match(/\d+/),
            commentId;

        if (!numbers) return;

        commentId = numbers[0];

        highligthComment(commentId);
    }

    /**
     * Comment removing action
     * @return {Event} click Event
     */
    function remove() {

        var itemClicked = this,
            targetId = itemClicked.dataset.id;

        if (!window.confirm('Подтвердите удаление комментария')) {

            return;
        }

        document.location = '/delete-comment/' + targetId;
    }

    return {
        init: init,
        reply: reply,
        remove: remove
    };
}();

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
* Notifications tips module
*/
module.exports = function () {

    __webpack_require__(11);

    var CSS_ = {
        wrapper: 'cdx-notifies-wrapper',
        notification: 'cdx-notifies',
        crossBtn: 'cdx-notifies-cross'
    };

    var wrapper_ = null;

    function prepare_() {

        if (wrapper_) {

            return true;
        }

        wrapper_ = document.createElement('DIV');
        wrapper_.classList.add(CSS_.wrapper);

        document.body.appendChild(wrapper_);
    }

    /**
    * @param {Object} options:
    *
    * @property {String} type    - type of notification. Just adds {CSS_.notification + '--' + type} class. 'notify' by default
    * @property {String} message - text to notify, can contains HTML
    * @property {String} time    - expiring time
    */
    function show(options) {

        prepare_();

        var notify = document.createElement('DIV'),
            cross = document.createElement('DIV'),
            message = options.message,
            type = options.type || 'notify',
            time = options.time || 8000;

        if (!message) {

            return;
        }

        notify.classList.add(CSS_.notification);
        notify.classList.add(CSS_.notification + '--' + type);
        notify.innerHTML = message;

        cross.classList.add(CSS_.crossBtn);
        cross.addEventListener('click', function () {

            notify.remove();
        });

        notify.appendChild(cross);
        wrapper_.appendChild(notify);

        notify.classList.add('bounceIn');

        window.setTimeout(function () {

            notify.remove();
        }, time);
    }

    return {
        show: show
    };
}({});

/***/ }),
/* 11 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = function () {

    /**
     * Menu block cache
     * @type {Element|null}
     */
    var menuHolder = null;

    /**
     * Activated menus
     * @type {Array}
     */
    var activated = [];

    /**
     * CSS class names
     * @type {Object}
     */
    var CSS = {
        menu: 'island-settings__menu',
        item: 'island-settings__item',
        showed: 'island-settings__menu--showed'

    };

    /**
     * Initialization
     * @param  {Object} settings  - initial settings
     */
    var init = function init(settings) {

        var menuTogglers = document.querySelectorAll(settings.selector),
            startIndex = activated.length,
            endIndex = menuTogglers.length + activated.length;

        for (var index = startIndex; index < endIndex; index++) {

            /**
             * Save initial object
             */
            activated.push({
                el: menuTogglers[index],
                settings: settings
            });

            prepareToggler(index, menuTogglers[index - startIndex]);
        }
    };

    /**
     * @public
     * Add event listener to the toggler
     * @param  {Number} index   - toggler initial index
     * @param  {Element} toggler
     */
    var prepareToggler = function prepareToggler(index, toggler) {

        /** Save initial selector to specify menu type */
        toggler.dataset.index = index;
        toggler.addEventListener('mouseover', menuTogglerHovered, false);
        toggler.addEventListener('mouseleave', menuTogglerBlurred, false);
    };

    /**
     * @private
     *
     * Island circled-icon mouseover handler
     *
     * @param {Event} event     mouseover-event
     */
    var menuTogglerHovered = function menuTogglerHovered() {

        var menuToggler = this,
            menuParams;

        /** Prevent mouseover handling multiple times */
        if (menuToggler.dataset.opened == 'true') {

            return;
        }

        menuToggler.dataset.opened = true;

        if (!menuHolder) {

            menuHolder = createMenu();
        }

        /**
         * Get current menu params
         * @type {Object}
         */
        menuParams = getMenuParams(menuToggler.dataset.index);

        console.assert(menuParams.items, 'Menu items missed');

        fill(menuParams.items, menuToggler);
        move(menuToggler);
    };

    /**
     * Toggler blur handler
     */
    var menuTogglerBlurred = function menuTogglerBlurred() {

        this.dataset.opened = false;
    };

    /**
     * Return menu parametres by toggler index
     * @param {Number}  index  - index got in init() method
     * @return {Object}
     */
    var getMenuParams = function getMenuParams(index) {

        return activated[index].settings;
    };

    /**
     * Fills menu with items
     * @param  {Array}   items     list of menu items
     * @param  {Element} toggler   islan menu icon with data-attributes
     */

    var fill = function fill(items, toggler) {

        var i, itemData, itemElement;

        menuHolder.innerHTML = '';

        for (i = 0; !!(itemData = items[i]); i++) {

            itemElement = createItem(itemData);

            /** Save index in dataset for edit-ability */
            itemElement.dataset.itemIndex = i;

            /** Pass all parametres stored in icon's dataset to the item's dataset */
            for (var attr in toggler.dataset) {

                itemElement.dataset[attr] = toggler.dataset[attr];
            }

            menuHolder.appendChild(itemElement);
        }
    };

    /**
    * @private
    * Creates an option block
    * @param {Object}   item          - menu item data
    * @param {String}   item.title    - title
    * @param {Function} item.handler  - click handler
    *
    * @return {Element} menu item with click handler
    */
    var createItem = function createItem(item) {

        var itemEl = document.createElement('LI');

        itemEl.classList.add(CSS.item);

        console.assert(item.title, 'islandSettings: item title is missed');

        itemEl.textContent = item.title;
        itemEl.addEventListener('click', itemClicked);

        return itemEl;
    };

    /**
     * Single callback for all items handler
     * Calls defined handler on itemClicked (Menu li Element) context and trasmits arguments
     */
    var itemClicked = function itemClicked() {

        var itemEl = this,
            togglerIndex = itemEl.dataset.index,
            itemIndex = itemEl.dataset.itemIndex,
            menuParams,
            handler,
            module,
            method,
            submethod,
            args;

        menuParams = getMenuParams(togglerIndex);

        module = menuParams.items[itemIndex].handler.module;
        method = menuParams.items[itemIndex].handler.method;
        submethod = menuParams.items[itemIndex].handler.submethod;

        if (module && method) {

            handler = codex[module][method];

            if (submethod) {

                handler = codex[module][method][submethod];
            }
        } else {

            handler = menuParams.items[itemIndex].handler;
        }

        args = menuParams.items[itemIndex].arguments;

        handler.call(itemEl, args || {});
    };

    /**
    * @private
    * Creates the dropdown menu
    */
    var createMenu = function createMenu() {

        var block = document.createElement('UL');

        block.classList.add(CSS.menu);

        return block;
    };

    /**
    * Appends a menu to the container
    * @param {Element} container - where to append menu
    */
    var move = function move(container) {

        container.appendChild(menuHolder);
        menuHolder.classList.add(CSS.showed);
    };

    /**
     * @public
     * @description Updates menu item
     * @param  {Number} togglerIndex   - Menu toggler initial index stored in toggler's dataset.index
     * @param  {Number} itemIndex      - Item index stored in item's dataset.itemIndex
     * @param  {String} title          - new title
     * @param  {Function} handler      - new handler
     * @param  {Object} args           - handler arguments
     */
    var updateItem = function updateItem(togglerIndex, itemIndex, title, handler, args) {

        console.assert(activated[togglerIndex], 'Toggler was not found by index');

        var currentMenu = activated[togglerIndex],
            currentItemEl = menuHolder.childNodes[itemIndex],
            currentItem;

        if (!currentMenu) {

            return;
        }

        currentItem = activated[togglerIndex].settings.items[itemIndex];

        if (title) {

            currentItem.title = title;
        }

        if (args) {

            currentItem.arguments = args;
        }

        if (handler && typeof handler == 'function') {

            currentItem.handler = handler;
        }

        /** Update opened menu item text  */
        if (menuHolder) {

            if (title) {

                currentItemEl.textContent = title;
            }
        }

        codex.core.log('item updated %o', 'islandSettings', 'info', currentItem);
    };

    return {
        init: init,
        updateItem: updateItem,
        prepareToggler: prepareToggler
    };
}();

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
* Module for comments textarea autoresize
*/
module.exports = function () {

    /**
    * Textareas initialization
    */
    var init = function init() {

        var textareas = document.getElementsByClassName('js-autoresizable');

        if (textareas.length) {

            for (var i = 0; i < textareas.length; i++) {

                addListener(textareas[i]);

                checkScrollHeight(textareas[i]);
            }
        }
    };

    /**
    * Add input event listener to textarea
    *
    * @param {Element} textarea — node which need to be able to autoresize
    */
    var addListener = function addListener(textarea) {

        textarea.addEventListener('input', textareaChanged, false);
    };

    /**
    * Hanging events on textareas
    */
    var textareaChanged = function textareaChanged(event) {

        var textarea = event.target;

        checkScrollHeight(textarea);
    };

    /**
    * Increasing textarea height
    */
    var checkScrollHeight = function checkScrollHeight(textarea) {

        if (textarea.scrollHeight > textarea.clientHeight) {

            textarea.style.height = textarea.scrollHeight + 'px';
        }
    };

    return {
        init: init,
        addListener: addListener
    };
}();

/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * User methods module
 */
module.exports = function () {

    /**
     * Manupulations with user photo
     * @return {Object} - Module
     */
    var photo = function () {

        /**
         * Mark elements with this name="" to dynamically update their sources
         * @type {String}
         */
        var updatableElementsName = 'js-img-updatable';

        /**
         * Changes user's photo
         * @param  {Event}  event   click event
         */
        var change = function change(event, transportType) {

            codex.transport.init({
                url: '/upload/' + transportType,
                success: uploaded,
                error: error
            });
        };

        /**
         * Uploading error
         * @param  {Object} error
         */
        var error = function error(uploadError) {

            console.log(uploadError);
        };

        /**
         * Photo uploading callback
         * @param  {String} response    server answer
         */
        var uploaded = function uploaded(response) {

            response = JSON.parse(response);

            if (!response.success) {

                codex.alerts.show({
                    type: 'error',
                    message: response.message || 'File uploading error :('
                });

                return;
            }

            console.assert(response.data && response.data.url, 'Wrong response data');

            updateAll(response.data.url);
        };

        /**
         * Updates all user photo sources
         * @uses   updatableElementsName  to find img tags
         * @param  {String} newSource
         */
        var updateAll = function updateAll(newSource) {

            var updatebleImages = document.getElementsByName(updatableElementsName);

            for (var i = updatebleImages.length - 1; i >= 0; i--) {

                updatebleImages[i].src = newSource;
            }
        };

        return {
            change: change
        };
    }();

    /**
     * Updatin user ROLE or STATUS
     * @type {{status, role}}
     */
    var promote = function () {

        var status = function status(args) {

            var itemClicked = this,
                userId = itemClicked.dataset.id,
                value = args.value;

            sendRequest(itemClicked, 'status', userId, value);
        };

        var role = function role(args) {

            var itemClicked = this,
                userId = itemClicked.dataset.id,
                value = args.value;

            sendRequest(itemClicked, 'role', userId, value);
        };

        /**
         * Change user role or status request
         * @param {Element} itemClicked     - menu item element
         * @param {string}  field           - field to save (role|status)
         * @param {Number}  userId          - target user id
         * @param {Number}  value           - new value
         */
        var sendRequest = function sendRequest(itemClicked, field, userId, value) {

            var url = '/user/' + userId + '/change/' + field,
                requestData = new FormData();

            requestData.append('value', value);

            codex.ajax.call({
                url: url,
                type: 'POST',
                data: requestData,
                beforeSend: function beforeSend() {

                    itemClicked.classList.add('loading');
                },
                success: function success(response) {

                    var menuIndex = itemClicked.dataset.index,
                        itemIndex = itemClicked.dataset.itemIndex;

                    response = JSON.parse(response);

                    itemClicked.classList.remove('loading');

                    codex.islandSettings.updateItem(menuIndex, itemIndex, response.buttonText, null, {
                        value: response.buttonValue
                    });

                    codex.alerts.show({
                        type: response.success ? 'success' : 'error',
                        message: response.message || 'Не удалось сохранить изменения'
                    });
                }
            });
        };

        return {
            status: status,
            role: role
        };
    }();

    var changePassword = function () {

        var form = null,
            input = null,
            button = null;
        /**
         * Shows form with input for current password
         *
         * @param lockButton
         */
        var showForm = function showForm(lockButton) {

            lockButton.classList.add('hide');

            form = document.getElementById('change-password-form');
            input = document.getElementById('change-password-input');

            form.classList.remove('hide');
        };

        /**
         * Handler for set password button
         *
         * @param form_
         */
        var set = function set(form_) {

            form = form_;
            requestChange(form, true);
            showSuccessMessage();
        };

        /**
         * Requests email with change password link
         *
         * @param button_
         * @param dontShowResponse - if TRUE, response will be ignored
         */
        var requestChange = function requestChange(button_, dontShowResponse) {

            button = button_;
            button.classList.add('loading');

            var data = new FormData();

            data.append('csrf', window.csrf);
            data.append('currentPassword', input ? input.value : '');

            codex.ajax.call({
                url: '/user/passchange',
                type: 'POST',
                data: data,
                success: dontShowResponse ? null : ajaxResponse,
                error: ajaxResponse
            });
        };

        /**
         * Repeat password change email sending
         *
         * @param button_
         */
        var repeatEmail = function repeatEmail(button_) {

            button_.classList.add('loading');

            var data = new FormData();

            data.append('csrf', window.csrf);
            data.append('repeatEmail', true);

            codex.ajax.call({
                url: '/user/passchange',
                type: 'POST',
                data: data,
                success: function success() {

                    button_.classList.remove('loading');

                    codex.alerts.show({
                        type: 'success',
                        message: 'Мы отправили на вашу почту письмо'
                    });
                },
                error: function error() {

                    button_.classList.remove('loading');

                    codex.alerts.show({
                        type: 'error',
                        message: 'Произошла ошибка'
                    });
                }
            });
        };

        var ajaxResponse = function ajaxResponse(response) {

            button.classList.remove('loading');

            try {

                response = JSON.parse(response);
            } catch (e) {

                response = { success: 0, message: 'Произошла ошибка' };
            }

            if (!response.success) {

                if (input) input.classList.add('form__input--invalid');

                codex.alerts.show({
                    type: 'error',
                    message: response.message
                });
            } else {

                showSuccessMessage();
                return;
            }
        };

        /**
         * Shows success email sending message
         *
         */
        var showSuccessMessage = function showSuccessMessage() {

            codex.alerts.show({
                type: 'success',
                message: 'Мы выслали инструкцию на вашу почту'
            });

            form.classList.add('hide');

            form = document.getElementById('change-password-success');
            form.classList.remove('hide');
        };

        return {
            showForm: showForm,
            requestChange: requestChange,
            set: set,
            repeatEmail: repeatEmail
        };
    }();

    /**
     * Working with bio
     */
    var bio = function () {

        /**
         * Edited textarea cache
         * @type {Element|null}
         */
        var textarea = null;

        /**
         * Edit bio click handler;
         * @param {Element} button  - button clicked
         */
        var edit = function edit(button) {

            var opened = button.querySelector('textarea');

            if (opened) {

                return;
            }

            textarea = document.createElement('TEXTAREA');
            textarea.innerHTML = button.textContent.trim();
            textarea.addEventListener('keydown', keydown);

            button.innerHTML = '';
            button.appendChild(textarea);

            textarea.focus();

            /** Fire autoresize */
            codex.autoresizeTextarea.addListener(textarea);
        };

        /**
         * Bio textarea keydowns
         * Sends via AJAX by ENTER
         */
        var keydown = function keydown(event) {

            if (event.keyCode == codex.core.keys.ENTER) {

                send(this.value);
                event.preventDefault();
            }
        };

        /**
         * Sends bio field
         * @param  {String} val textarea value
         */
        var send = function send(val) {

            if (!val.trim()) {

                codex.alerts.show({
                    type: 'error',
                    message: 'Write something about yourself'
                });
                return;
            }

            var formData = new FormData();

            formData.append('bio', val);
            formData.append('csrf', window.csrf);

            codex.ajax.call({
                type: 'POST',
                url: '/user/updateBio',
                data: formData,
                beforeSend: beforeSend,
                success: saved
            });
        };

        /**
         * Simple beforeSend method
         */
        var beforeSend = function beforeSend() {

            textarea.classList.add('loading');
        };

        /**
         * Success saving callback
         */
        var saved = function saved(response) {

            response = JSON.parse(response);

            if (!response.success || !response.bio) {

                textarea.classList.remove('loading');
                codex.alerts.show({
                    type: 'error',
                    message: 'Saving error, sorry'
                });
                return;
            }

            var newBio = document.createTextNode(response.bio || '');

            /** Update user's CSRF token */
            window.csrf = response.csrf;

            codex.core.replace(textarea, newBio);
        };

        return {
            edit: edit
        };
    }();

    var email = function () {

        var currentEmail = null,
            loadingButton = null;

        var saved = function saved(response) {

            try {

                response = JSON.parse(response);

                if (response.success) {

                    codex.core.replace(currentEmail.parentNode, codex.core.parseHTML(response.island)[0]);

                    codex.alerts.show({
                        type: 'success',
                        message: 'Адрес почты обновлен. Теперь вам нужно подтвердить его, перейдя по ссылке в письме.'
                    });

                    currentEmail = null;
                    return;
                }
            } catch (e) {}

            loadingButton.classList.remove('loading');

            codex.alerts.show({
                type: 'error',
                message: response.message || 'Произошла ошибка, попробуйте позже'
            });
        };

        var send = function send() {

            if (currentEmail.value.trim() == '') {

                codex.alerts.show({
                    type: 'error',
                    message: 'Введите email'
                });

                return;
            }

            loadingButton = this;
            loadingButton.classList.add('loading');

            var data = new FormData();

            data.append('email', currentEmail.value);
            data.append('csrf', window.csrf);

            codex.ajax.call({
                url: 'user/changeEmail',
                type: 'POST',
                data: data,
                success: saved,
                error: saved
            });
        };

        var sendConfirmation = function sendConfirmation(button) {

            var success = function success(response) {

                response = JSON.parse(response);

                codex.alerts.show({
                    type: 'success',
                    message: response.message
                });
                button.classList.remove('loading');
            };

            button.classList.add('loading');

            codex.ajax.call({
                url: '/ajax/confirmation-email',
                success: success
            });
        };

        var changed = function changed(input) {

            if (currentEmail) {

                return;
            }

            currentEmail = input;

            var saveButton = document.createElement('BUTTON'),
                sendButton = input.parentNode.querySelector('button');

            if (sendButton) sendButton.classList.remove('master');

            saveButton.classList.add('button', 'master');
            saveButton.textContent = 'Сохранить';

            saveButton.addEventListener('click', send);

            input.oninput = null;
            input.parentNode.appendChild(saveButton);
        };

        var set = function set(button) {

            button.classList.add('hide');

            var form = document.getElementById('set-email-form');

            form.classList.remove('hide');

            currentEmail = document.getElementById('set-email-input');
        };

        return {
            sendConfirmation: sendConfirmation,
            changed: changed,
            send: send,
            set: set
        };
    }();

    return {
        changePassword: changePassword,
        promote: promote,
        photo: photo,
        bio: bio,
        email: email
    };
}();

/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var sharer = {

    init: function init() {

        var shareButtons = document.querySelectorAll('.js-share');

        for (var i = shareButtons.length - 1; i >= 0; i--) {

            shareButtons[i].addEventListener('click', sharer.click, true);
        }
    },

    shareVk: function shareVk(data) {

        var link = 'https://vk.com/share.php?';

        link += 'url=' + data.url;
        link += '&title=' + data.title;
        link += '&description=' + data.desc;
        link += '&image=' + data.img;
        link += '&noparse=true';

        this.popup(link, 'vkontakte');
    },

    shareFacebook: function shareFacebook(data) {

        var FB_APP_ID = 1740455756240878,
            link = 'https://www.facebook.com/dialog/share?display=popup';

        link += '&app_id=' + FB_APP_ID;
        link += '&href=' + data.url;
        link += '&redirect_uri=' + document.location.href;

        this.popup(link, 'facebook');
    },

    shareTwitter: function shareTwitter(data) {

        var link = 'https://twitter.com/share?';

        link += 'text=' + data.title;
        link += '&url=' + data.url;
        link += '&counturl=' + data.url;

        this.popup(link, 'twitter');
    },

    shareTelegram: function shareTelegram(data) {

        var link = 'https://telegram.me/share/url';

        link += '?text=' + data.title;
        link += '&url=' + data.url;

        this.popup(link, 'telegram');
    },

    popup: function popup(url, socialType) {

        window.open(url, '', 'toolbar=0,status=0,width=626,height=436');

        /**
         * Write analytics goal
         */
        if (window.yaCounter32652805) {

            window.yaCounter32652805.reachGoal('article-share', function () {}, this, { type: socialType, url: url });
        }
    },

    click: function click(event) {

        var target = event.target;

        /**
         * Social provider stores in data 'shareType' attribute on share-button
         * But click may be fired on child-element in button, so we need to handle it.
         */
        var type = target.dataset.shareType || target.parentNode.dataset.shareType;

        if (!sharer[type]) return;

        /**
         * Sanitize share params
         * @todo test for taint strings
         */
        // for (key in window.shareData){
        //      window.shareData[key] = encodeURIComponent(window.shareData[key]);
        // }

        var shareData = {

            url: target.dataset.url || target.parentNode.dataset.url,
            title: target.dataset.title || target.parentNode.dataset.title,
            desc: target.dataset.desc || target.parentNode.dataset.desc,
            img: target.dataset.img || target.parentNode.dataset.title

        };

        /**
         * Fire click handler
         */

        sharer[type](shareData);
    }

};

module.exports = sharer;

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


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

module.exports = function () {

    /**
     * CodeX Editor Personality-tool
     * @see  https://github.com/codex-editor/personality
     */
    var personalityTool = __webpack_require__(17);

    /**
     * CodeX Editor link embed tool
     * @see  https://github.com/codex-editor/link
     */
    var linkTool = __webpack_require__(18);

    var editorIsReady = false,
        submitButton = null,
        settings = {
        hideEditorToolbar: false,
        titleId: 'editorWritingTitle',
        initialBlockPlugin: 'paragraph',
        data: { items: [] },
        resources: [],
        holderId: null,
        pageId: 0,
        parentId: 0
    };

    /**
     * Prepare editor's resourses
     *
     * @param  {Object} initSettings    base settings for editor
     * @return {Promise}            all editor's resources are ready
     */
    var prepare = function prepare(initSettings) {

        mergeSettings(initSettings);

        return loadEditorResources(settings.resources).then(function () {

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
    }

    /**
     * Run editor
     */
    function startEditor() {

        /**
         * @todo get from server
         */
        var EDITOR_IMAGE = 1;
        var EDITOR_FILE = 2;
        var EDITOR_PERSONALITY = 6;

        codex.editor.start({

            holderId: settings.holderId,
            initialBlockPlugin: settings.initialBlockPlugin,
            hideToolbar: settings.hideEditorToolbar,
            sanitizer: {
                tags: {
                    p: {},
                    a: {
                        href: true,
                        target: '_blank'
                    }
                }
            },
            tools: {
                paragraph: {
                    type: 'paragraph',
                    iconClassname: 'ce-icon-paragraph',
                    render: window.paragraph.render,
                    validate: window.paragraph.validate,
                    save: window.paragraph.save,
                    allowedToPaste: true,
                    showInlineToolbar: true,
                    destroy: window.paragraph.destroy,
                    allowRenderOnPaste: true
                },
                header: {
                    type: 'header',
                    iconClassname: 'ce-icon-header',
                    appendCallback: window.header.appendCallback,
                    makeSettings: window.header.makeSettings,
                    render: window.header.render,
                    validate: window.header.validate,
                    save: window.header.save,
                    destroy: window.header.destroy,
                    displayInToolbox: true
                },
                image: {
                    type: 'image',
                    iconClassname: 'ce-icon-picture',
                    appendCallback: window.image.appendCallback,
                    prepare: window.image.prepare,
                    makeSettings: window.image.makeSettings,
                    render: window.image.render,
                    save: window.image.save,
                    destroy: window.image.destroy,
                    isStretched: true,
                    showInlineToolbar: true,
                    displayInToolbox: true,
                    renderOnPastePatterns: window.image.pastePatterns,
                    config: {
                        uploadImage: '/upload/' + EDITOR_IMAGE,
                        uploadFromUrl: ''
                    }
                },
                attaches: {
                    type: 'attaches',
                    displayInToolbox: true,
                    iconClassname: 'cdx-attaches__icon',
                    prepare: window.cdxAttaches.prepare,
                    render: window.cdxAttaches.render,
                    save: window.cdxAttaches.save,
                    validate: window.cdxAttaches.validate,
                    destroy: window.cdxAttaches.destroy,
                    appendCallback: window.cdxAttaches.appendCallback,
                    config: {
                        fetchUrl: '/upload/' + EDITOR_FILE,
                        maxSize: codex.appSettings.uploadMaxSize * 1000
                    }
                },
                list: {
                    type: 'list',
                    iconClassname: 'ce-icon-list-bullet',
                    make: window.list.make,
                    appendCallback: null,
                    makeSettings: window.list.makeSettings,
                    render: window.list.render,
                    validate: window.list.validate,
                    save: window.list.save,
                    destroy: window.list.destroy,
                    displayInToolbox: true,
                    showInlineToolbar: true,
                    enableLineBreaks: true,
                    allowedToPaste: true
                },
                link: {
                    type: 'link',
                    iconClassname: 'cdx-link-icon',
                    displayInToolbox: true,
                    prepare: linkTool.prepare,
                    render: linkTool.render,
                    makeSettings: linkTool.settings,
                    save: linkTool.save,
                    destroy: linkTool.destroy,
                    validate: linkTool.validate,
                    config: {
                        fetchURL: '/fetchURL',
                        defaultStyle: 'smallCover'
                    }
                },
                raw: {
                    type: 'raw',
                    displayInToolbox: true,
                    iconClassname: 'raw-plugin-icon',
                    render: window.rawPlugin.render,
                    save: window.rawPlugin.save,
                    validate: window.rawPlugin.validate,
                    destroy: window.rawPlugin.destroy,
                    enableLineBreaks: true,
                    allowPasteHTML: true
                },
                personality: {
                    type: 'personality',
                    displayInToolbox: true,
                    iconClassname: 'cdx-personality-icon',
                    prepare: personalityTool.prepare,
                    render: personalityTool.render,
                    save: personalityTool.save,
                    validate: personalityTool.validate,
                    destroy: personalityTool.destroy,
                    enableLineBreaks: true,
                    showInlineToolbar: true,
                    config: {
                        uploadURL: '/upload/' + EDITOR_PERSONALITY
                    }
                }
            },

            data: settings.data
        });

        var titleInput = document.getElementById(settings.titleId);

        /**
         * Focus at the title
         */
        titleInput.focus();
        titleInput.addEventListener('keydown', titleKeydownHandler);
    }

    /**
     * Title input keydowns
     * @description  By ENTER, sets focus on editor
     * @param  {Event} event  - keydown event
     */
    var titleKeydownHandler = function titleKeydownHandler(event) {

        /* Set focus on Editor by Enter     */
        if (event.keyCode == codex.core.keys.ENTER) {

            event.preventDefault();

            focusRedactor();
        }
    };

    /**
     * Temporary scheme to focus Codex Editor first-block
     */
    var focusRedactor = function focusRedactor() {

        var firstBlock = codex.editor.nodes.redactor.firstChild,
            contentHolder = firstBlock.firstChild,
            firstToolWrapper = contentHolder.firstChild,
            aloneTextNode;

        /**
         * Caret will not be placed in empty textNode, so we need textNode with zero-width char
         */
        aloneTextNode = document.createTextNode('\u200B');

        /**
         * We need to append manually created textnode before returning
         */
        firstToolWrapper.appendChild(aloneTextNode);

        codex.editor.caret.set(firstToolWrapper, 0, 0);
    };

    /**
     * Public function for run editor
     */
    var init = function init() {

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
    var open = function open(targetClicked, formId, hidePlaceholderClass) {

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
    var loadEditorResources = function loadEditorResources(resources) {

        return Promise.all(resources.map(loadResource));
    };

    /**
     * Loads resource
     *
     * @param  {Object} resource name and paths for js and css
     * @return {Promise}
     */
    function loadResource(resource) {

        var name = resource.name,
            scriptUrl = resource.path.script,
            styleUrl = resource.path.style;

        return Promise.all([codex.loader.importScript(scriptUrl, name), codex.loader.importStyle(styleUrl, name)]);
    }

    /**
    * Prepares form to submit
    */
    var getForm = function getForm() {

        var atlasForm = document.forms.atlas;

        if (!atlasForm) return;

        /** CodeX.Editor */
        var JSONinput = document.createElement('TEXTAREA');

        JSONinput.name = 'content';
        JSONinput.id = 'json_result';
        JSONinput.hidden = true;
        atlasForm.appendChild(JSONinput);

        /**
         * Save blocks
         */
        codex.editor.saver.saveBlocks();

        return atlasForm;
    };

    /**
     * Send ajax request with writing form data
     * @param button - submit button (needed to add loading animation)
     */
    var submit = function submit(button) {

        var title = document.forms.atlas.elements['title'],
            form;

        if (title.value.trim() === '') {

            codex.editor.notifications.notification({
                type: 'warn',
                message: 'Заполните заголовок'
            });

            return;
        }

        form = getForm();

        submitButton = button;

        submitButton.classList.add('loading');

        window.setTimeout(function () {

            form.elements['content'].innerHTML = JSON.stringify({ items: codex.editor.state.jsonOutput });

            codex.ajax.call({
                url: '/p/save',
                data: new FormData(form),
                success: submitResponse,
                type: 'POST'
            });
        }, 500);
    };

    /**
     * Response handler for page saving
     * @param response
     */
    var submitResponse = function submitResponse(response) {

        submitButton.classList.remove('loading');

        response = JSON.parse(response);

        if (response.success) {

            window.location = response.redirect;
            return;
        }

        codex.editor.notifications.notification({
            type: 'warn',
            message: response.message
        });
    };

    /**
    * Submits writing form for opening in full-screan page without saving
    */
    var openEditorFullscreen = function openEditorFullscreen() {

        var form = getForm();

        window.setTimeout(function () {

            form.elements['content'].innerHTML = JSON.stringify({ items: codex.editor.state.jsonOutput });

            form.submit();
        }, 500);
    };

    return {
        init: init,
        prepare: prepare,
        open: open,
        openEditorFullscreen: openEditorFullscreen,
        submit: submit
    };
}();

/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var cdxEditorPersonality =
/******/function (modules) {
    // webpackBootstrap
    /******/ // The module cache
    /******/var installedModules = {};
    /******/
    /******/ // The require function
    /******/function __webpack_require__(moduleId) {
        /******/
        /******/ // Check if module is in cache
        /******/if (installedModules[moduleId]) {
            /******/return installedModules[moduleId].exports;
            /******/
        }
        /******/ // Create a new module (and put it into the cache)
        /******/var module = installedModules[moduleId] = {
            /******/i: moduleId,
            /******/l: false,
            /******/exports: {}
            /******/ };
        /******/
        /******/ // Execute the module function
        /******/modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ // Flag the module as loaded
        /******/module.l = true;
        /******/
        /******/ // Return the exports of the module
        /******/return module.exports;
        /******/
    }
    /******/
    /******/
    /******/ // expose the modules object (__webpack_modules__)
    /******/__webpack_require__.m = modules;
    /******/
    /******/ // expose the module cache
    /******/__webpack_require__.c = installedModules;
    /******/
    /******/ // identity function for calling harmony imports with the correct context
    /******/__webpack_require__.i = function (value) {
        return value;
    };
    /******/
    /******/ // define getter function for harmony exports
    /******/__webpack_require__.d = function (exports, name, getter) {
        /******/if (!__webpack_require__.o(exports, name)) {
            /******/Object.defineProperty(exports, name, {
                /******/configurable: false,
                /******/enumerable: true,
                /******/get: getter
                /******/ });
            /******/
        }
        /******/
    };
    /******/
    /******/ // getDefaultExport function for compatibility with non-harmony modules
    /******/__webpack_require__.n = function (module) {
        /******/var getter = module && module.__esModule ?
        /******/function getDefault() {
            return module['default'];
        } :
        /******/function getModuleExports() {
            return module;
        };
        /******/__webpack_require__.d(getter, 'a', getter);
        /******/return getter;
        /******/
    };
    /******/
    /******/ // Object.prototype.hasOwnProperty.call
    /******/__webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    /******/
    /******/ // __webpack_public_path__
    /******/__webpack_require__.p = "";
    /******/
    /******/ // Load entry module and return exports
    /******/return __webpack_require__(__webpack_require__.s = 4);
    /******/
}(
/************************************************************************/
/******/[
/* 0 */
/***/function (module, exports) {

    /**
     * Interface for Personality tool
     * @author CodeX Team
     */
    module.exports = function (ui) {

        'use strict';

        /**
         * CSS class names
         * @type {Object}
         */

        ui.css = {
            holder: 'cdx-personality',
            name: 'cdx-personality__name',
            cite: 'cdx-personality__cite',
            url: 'cdx-personality__url',
            photo: 'cdx-personality__photo',
            photoPreview: 'cdx-personality__photo--preview'
        };

        /**
         * Creates Element
         * @param {string} tagName
         * @param {string} className
         * @param {object} properties - allow to assign properties
         */
        var create = function create(tagName, className, properties) {

            var el = document.createElement(tagName);

            if (className) el.className = className;

            if (properties) {

                for (var name in properties) {

                    el[name] = properties[name];
                }
            }

            return el;
        };

        /**
         * Creates plugin holder
         * @return {Element}
         */
        ui.holder = function () {

            return create('DIV', ui.css.holder);
        };

        /**
         * Input for personality name
         * @param {String} savedName
         * @return {Element}
         */
        ui.nameInput = function (savedName) {

            var name = create('INPUT', ui.css.name);

            name.placeholder = 'Введите имя';
            name.value = savedName || '';

            return name;
        };

        /**
         * Input for personality description
         * @param {String} savedCite
         * @return {Element}
         */
        ui.citeInput = function (savedCite) {

            var div = create('DIV', ui.css.cite);

            div.contentEditable = true;
            div.setAttribute('data-placeholder', 'Должность или другая информация');
            div.innerHTML = savedCite || '';

            return div;
        };

        /**
         * Input for personality URL
         * @param {String} savedUrl
         * @return {Element}
         */
        ui.urlInput = function (savedUrl) {

            var url = create('INPUT', ui.css.url);

            url.placeholder = 'Ссылка на страницу человека';
            url.value = savedUrl || '';

            return url;
        };

        /**
        * @return {Element}
        * @param {String} savedPhoto image URL
        */
        ui.photo = function (savedPhoto) {

            var photo = create('DIV', ui.css.photo),
                img;

            if (savedPhoto) {

                img = document.createElement('IMG');
                img.src = savedPhoto;
                photo.appendChild(img);
            }

            return photo;
        };

        return ui;
    }({});

    /***/
},
/* 1 */
/***/function (module, exports, __webpack_require__) {

    /**
     * Saver module for Personality tool
     * @author  CodeX Team
     */
    module.exports = function (saver) {

        'use strict';

        var ui = __webpack_require__(0);

        /**
         * Extracts data from block
         * @param  {Element} block
         * @return {Object}
         */
        saver.extractData = function (block) {

            var nameEl = block.querySelector('.' + ui.css.name),
                citeEl = block.querySelector('.' + ui.css.cite),
                urlEl = block.querySelector('.' + ui.css.url),
                photo = block.querySelector('.' + ui.css.photo + ' img'),
                toolData = {},
                sanitizerConfig = {
                tags: {
                    p: {},
                    a: {
                        href: true,
                        target: '_blank',
                        rel: 'nofollow'
                    },
                    i: {},
                    b: {}
                }
            },
                cite;

            cite = citeEl.innerHTML;
            cite = codex.editor.content.wrapTextWithParagraphs(cite);
            cite = codex.editor.sanitizer.clean(cite, sanitizerConfig);

            toolData.name = nameEl.value;
            toolData.cite = cite;
            toolData.url = urlEl.value;
            toolData.photo = null;

            if (photo) {

                toolData.photo = photo.src;
            }

            return toolData;
        };

        /**
         * Validation method
         * @param  {Object} toolData - saving data that needs to check
         * @return {Boolean}         - TRUE if data is value
         */
        saver.validate = function (toolData) {

            /** Dont allow empty name */
            if (!toolData.name.trim()) {

                return false;
            }

            return true;
        };

        return saver;
    }({});

    /***/
},
/* 2 */
/***/function (module, exports, __webpack_require__) {

    /**
     * Uploader module for Personality tool
     * @author  CodeX Team
     */
    module.exports = function (uploader) {

        'use strict';

        var ui = __webpack_require__(0);

        /**
         * External config
         * @type {Object}
         */
        uploader.config = {
            uploadURL: ''
        };

        /**
         * Updates preview image
         * @return {Element} preview - preview IMG
         * @return {String} src      - preview image source
         */
        function updatePreview(preview, src) {

            preview.src = src;
        }

        /**
         * Makes images preview
         * @param  {HTMLElement} holder
         */
        function makePreview(holder) {

            var input = codex.editor.transport.input,
                files = input.files,
                reader,
                preview = document.createElement('IMG');

            console.assert(files, 'There is no files in input');

            reader = new window.FileReader();
            reader.readAsDataURL(files[0]);

            preview.classList.add(ui.css.photoPreview);
            holder.innerHTML = '';
            holder.appendChild(preview);

            reader.onload = function (e) {

                updatePreview(preview, e.target.result);
            };

            return preview;
        }

        /**
         * Before send method
         * @this {Button clicked}
         */
        function beforeSend() {

            var selectPhotoButton = this;

            /**
             * Returned value will be passed as context of success and error
             */
            return makePreview(selectPhotoButton);
        }

        /**
         * Success uploading hanlder
         * @this - beforeSend result
         * @param {String} response - upload response
         *
         * Expected response format:
         * {
         *     success : 1,
         *     data: {
         *         url : 'site/filepath.jpg'
         *     }
         * }
         */
        function success(response) {

            var preview = this;

            response = JSON.parse(response);

            console.assert(response.data && response.data.url, 'Expected photo URL was not found in response data');

            updatePreview(preview, response.data.url);
            preview.classList.remove(ui.css.photoPreview);
        }

        /**
         * Error during upload handler
         * @this {Element} preview
         */
        function failed() {

            var preview = this;

            codex.editor.notifications.notification({ type: 'error', message: 'Ошибка во время загрузки. Попробуйте другой файл' });

            preview.remove();
        }

        /**
         * Select file click listener
         */
        uploader.photoClicked = function () {

            var button = this;

            codex.editor.transport.selectAndUpload({
                url: uploader.config.uploadURL,
                multiple: false,
                accept: 'image/*',
                beforeSend: beforeSend.bind(button),
                success: success,
                error: failed
            });
        };

        return uploader;
    }({});

    /***/
},
/* 3 */
/***/function (module, exports) {

    // removed by extract-text-webpack-plugin

    /***/},
/* 4 */
/***/function (module, exports, __webpack_require__) {

    /**
     * Personality
     * Tool for CodeX Editor
     *
     * @author CodeX Team
     * @version 1.0.0
     * @see https://github.com/codex-editor/personality
     *
     * @description Provides public interface methods
     */
    module.exports = function () {

        'use strict';

        /**
         * Styleheets
         */

        __webpack_require__(3);

        var ui = __webpack_require__(0);
        var saver = __webpack_require__(1);
        var uploader = __webpack_require__(2);

        /**
         * @param {Object} toolData
         * @param {string} toolData.name    - Personality name
         * @param {string} toolData.cite    - Personality cite
         * @param {string} toolData.url     - Personality url
         * @param {string} toolData.photo   - Personality photo URL
         *
         * @return {Element} personality tool block
         */
        function render(toolData) {

            toolData = toolData || {};

            var pluginHolder = ui.holder(),
                name = ui.nameInput(toolData.name),
                cite = ui.citeInput(toolData.cite),
                url = ui.urlInput(toolData.url),
                photo = ui.photo(toolData.photo);

            pluginHolder.appendChild(photo);
            pluginHolder.appendChild(name);
            pluginHolder.appendChild(cite);
            pluginHolder.appendChild(url);

            photo.addEventListener('click', uploader.photoClicked);

            return pluginHolder;
        }

        /**
         * Prepares plugin
         * @param  {Object} config
         * @return {Promise}
         */
        function prepare(config) {

            return Promise.resolve().then(function () {

                uploader.config = config;
            });
        }

        /**
         * Validation
         * @param  {Object} savingData - tool presaved data
         * @fires saver.validate
         * @return {Boolean}
         */
        function validate(savingData) {

            return saver.validate(savingData);
        }

        /**
         * Destroy method
         */
        function destroy() {

            window.cdxEditorPersonality = null;
        }

        /**
         * Saving method
         * @param  {Element} block   - plugin content
         * @return {Object}          - personality data
         */
        function save(block) {

            var data = saver.extractData(block);

            return data;
        }

        return {
            render: render, save: save, validate: validate, destroy: destroy, prepare: prepare
        };
    }();

    /***/
}]
/******/);

/*** EXPORTS FROM exports-loader ***/
module.exports = cdxEditorPersonality;

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var cdxEditorLink = function (e) {
  function t(r) {
    if (n[r]) return n[r].exports;var i = n[r] = { i: r, l: !1, exports: {} };return e[r].call(i.exports, i, i.exports, t), i.l = !0, i.exports;
  }var n = {};return t.m = e, t.c = n, t.i = function (e) {
    return e;
  }, t.d = function (e, n, r) {
    t.o(e, n) || Object.defineProperty(e, n, { configurable: !1, enumerable: !0, get: r });
  }, t.n = function (e) {
    var n = e && e.__esModule ? function () {
      return e.default;
    } : function () {
      return e;
    };return t.d(n, "a", n), n;
  }, t.o = function (e, t) {
    return Object.prototype.hasOwnProperty.call(e, t);
  }, t.p = "", t(t.s = 6);
}([function (e, t, n) {
  "use strict";
  e.exports = function () {
    function e() {
      var e = document.createElement("INPUT");return e.type = "input", e.classList.add(s.inputElement), e.placeholder = "Paste Link...", e;
    }function t() {
      var e = document.createElement("DIV");return e.classList.add(s.linkHolder), e;
    }function r(e) {
      var n = t(),
          r = document.createElement("DIV"),
          i = document.createElement("DIV"),
          o = document.createElement("DIV"),
          l = document.createElement("A");switch (n.dataset.style = e.style, n.classList.add(s.linkHolder, s.linkRendered), i.classList.add(s.cover), i.style.backgroundImage = 'url("' + e.image + '")', r.textContent = e.title, r.classList.add(s.embedTitle), o.textContent = e.description, o.classList.add(s.description), l.textContent = e.linkText, l.href = e.linkUrl, l.classList.add(s.anchor), n.appendChild(i), e.style) {case "smallCover":
          i.classList.add(s.smallCover);break;case "bigCover":
          i.classList.add(s.bigCover);}return n.appendChild(r), n.appendChild(o), n.appendChild(l), n;
    }function i() {
      var e = document.createElement("DIV");return e.classList.add(s.linkSettings), e;
    }function o(e, t) {
      var n = document.createElement("SPAN");return n.textContent = e[t], n.classList.add(s.linkSettingsItem), n;
    }function l() {
      var e = document.createElement("LABEL");return e.classList.add(s.label), e;
    }function c(e) {
      var t = codex.editor.content.currentNode,
          n = e || t.querySelector("." + s.linkHolder),
          r = n.querySelector("." + s.embedTitle),
          i = n.querySelector("." + s.cover),
          o = i.style.backgroundImage.match(/http?.[^"]+/),
          l = n.querySelector("." + s.description),
          c = n.querySelector("." + s.anchor),
          a = {};return a.style = n.dataset.style, a.image = o, a.title = r.textContent, a.description = l.innerHTML, a.linkText = c.innerHTML, a.linkUrl = c.href, a;
    }var s = { linkHolder: "cdx-link-tool", linkRendered: "cdx-link-tool--rendered", embedTitle: "cdx-link-tool__title", cover: "cdx-link-tool__cover", smallCover: "cdx-link-tool__cover--small", bigCover: "cdx-link-tool__cover--big", description: "cdx-link-tool__description", anchor: "cdx-link-tool__anchor", inputElement: "cdx-link-tool__input", inputError: "cdx-link-tool__input--error", label: "cdx-link-tool__label", labelLoading: "cdx-link-tool__label--loading", labelFinish: "cdx-link-tool__label--finish", linkSettings: "link-settings", linkSettingsItem: "link-settings__item", settingsItemActive: "link-settings__item--active" };return n(7), { css: s, drawInput: e, drawLabel: l, drawLinkHolder: t, drawEmbed: r, drawSettingsHolder: i, drawSettingsItem: o, getDataFromHTML: c };
  }();
}, function (e, t, n) {
  "use strict";
  e.exports = function () {
    function e(e) {
      var t = void 0;if (e && e.style) t = r.drawEmbed(e);else {
        var n = void 0;t = r.drawLinkHolder(), n = r.drawInput(), n.addEventListener("paste", i.URLPasted.bind(n)), t.appendChild(n);
      }return t;
    }function t(t) {
      return e(t);
    }return t;
  }();var r = n(0),
      i = n(5);
}, function (e, t, n) {
  "use strict";
  var r = "function" == typeof Symbol && "symbol" == _typeof(Symbol.iterator) ? function (e) {
    return typeof e === "undefined" ? "undefined" : _typeof(e);
  } : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e === "undefined" ? "undefined" : _typeof(e);
  };e.exports = function (e) {
    return e.config = null, e.prepare = function (t) {
      return new Promise(function (n, i) {
        "object" === (void 0 === t ? "undefined" : r(t)) && t.fetchURL ? (e.config = t, n()) : i("Cant initialize plugin without fetch server");
      });
    }, e;
  }({});
}, function (e, t, n) {
  "use strict";
  e.exports = function () {
    function e(e) {
      var t = { tags: {} },
          n = { tags: { p: {} } },
          r = { tags: { p: {}, a: { href: !0, target: "_blank", rel: "nofollow" }, b: {}, i: {} } };return "bigCover" != e.style && "smallCover" != e.style && (e.style = "smallCover"), e.title = codex.editor.sanitizer.clean(e.title, n), e.description = codex.editor.sanitizer.clean(e.description, r), e.linkText = codex.editor.sanitizer.clean(e.linkText, t), e.linkUrl = codex.editor.sanitizer.clean(e.linkUrl, t), e.image = codex.editor.sanitizer.clean(e.image, t), e;
    }function t(t) {
      return e(r.getDataFromHTML(t));
    }return t;
  }();var r = n(0);
}, function (e, t, n) {
  "use strict";
  e.exports = function () {
    function e() {
      var e = codex.editor.content.currentNode,
          n = e.querySelector("." + r.css.linkHolder),
          i = r.drawSettingsHolder(),
          o = { smallCover: "Маленькая обложка", bigCover: "Большая обложка" };for (var l in o) {
        var c = r.drawSettingsItem(o, l);c.dataset.style = l, c.addEventListener("click", t), n.dataset.style == l && c.classList.add(r.css.settingsItemActive), i.appendChild(c);
      }return i;
    }function t() {
      var e = codex.editor.content.currentNode;switch (this.dataset.style) {case "smallCover":
          n(e);break;case "bigCover":
          o(e);}codex.editor.toolbar.settings.close();
    }function n(e) {
      var t = r.getDataFromHTML(),
          n = void 0;t.style = "smallCover", n = i(t), codex.editor.content.switchBlock(e, n, "link");
    }function o(e) {
      var t = r.getDataFromHTML(),
          n = void 0;t.style = "bigCover", n = i(t), codex.editor.content.switchBlock(e, n, "link");
    }return e;
  }();var r = n(0),
      i = n(1);
}, function (e, t, n) {
  "use strict";
  e.exports = function () {
    function e(e) {
      var o = this,
          c = void 0,
          s = void 0;c = e.clipboardData || window.clipboardData, s = c.getData("Text"), o.classList.remove(r.css.inputError), codex.editor.core.ajax({ url: i.config.fetchURL + "?url=" + s, type: "GET", beforeSend: t.bind(o), success: n, error: l });
    }function t() {
      var e = this,
          t = r.drawLabel(),
          n = e.parentNode;return n.insertBefore(t, e), window.setTimeout(function () {
        t.classList.add(r.css.labelLoading);
      }, 50), n;
    }function n(e) {
      var t = this,
          n = void 0,
          c = t.querySelector("." + r.css.label),
          s = void 0;c.classList.add(r.css.labelFinish);try {
        n = JSON.parse(e), n.style = i.config.defaultStyle, n.success || 1 === n.success ? (s = o(n), window.setTimeout(function () {
          codex.editor.content.switchBlock(t, s, "link");
        }, 2500)) : l.call(t, n.message);
      } catch (e) {
        l.call(t);
      }
    }function l(e) {
      var t = this,
          n = t.querySelector("." + r.css.label);t.querySelector("." + r.css.inputElement).classList.add(r.css.inputError), n.remove(), codex.editor.notifications.notification({ type: "error", message: e || "Unsupported link" });
    }return { URLPasted: e };
  }();var r = n(0),
      i = n(2),
      o = n(1);
}, function (e, t, n) {
  "use strict";
  e.exports = function () {
    var e = n(1),
        t = n(3),
        r = n(4);return { prepare: n(2).prepare, render: e, save: t, settings: r };
  }();
}, function (e, t) {}]);

/*** EXPORTS FROM exports-loader ***/
module.exports = cdxEditorLink;

/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {

    importScript: function importScript(scriptPath, instanceName) {

        return new Promise(function (resolve, reject) {

            var prefixJS = 'cdx-script-',
                script;
            /**
             * @todo make importStyle static function,
             * because now in Promise construction
             * this.prefixJS is undefined
             */

            /** Script is already loaded */
            if (!instanceName) {

                reject('Instance name is missed');
            } else if (document.getElementById(prefixJS + instanceName)) {

                resolve(scriptPath);
            }

            script = document.createElement('SCRIPT');
            script.async = true;
            script.defer = true;
            script.id = codex.loader.prefixJS + instanceName;

            script.onload = function () {

                resolve(scriptPath);
            };

            script.onerror = function () {

                reject(scriptPath);
            };

            script.src = scriptPath;
            document.head.appendChild(script);
        });
    },

    importStyle: function importStyle(stylePath, instanceName) {

        return new Promise(function (resolve, reject) {

            var style,
                prefixCSS = 'cdx-style-';
            /**
             * @todo make importStyle static function,
             * because now in Promise construction
             * this.prefixCSS is undefined
             */

            /** Style is already loaded */
            if (!instanceName) {

                reject('Instance name is missed');
            } else if (document.getElementById(prefixCSS + instanceName)) {

                resolve(stylePath);
            }

            style = document.createElement('LINK');
            style.type = 'text/css';
            style.href = stylePath;
            style.rel = 'stylesheet';
            style.id = codex.loader.prefixCSS + instanceName;

            style.onload = function () {

                resolve(stylePath);
            };

            style.onerror = function () {

                reject(stylePath);
            };

            style.src = stylePath;
            document.head.appendChild(style);
        });
    }

};

/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
* Module for scroll-up button
* @author @scytem @guryn
*/

module.exports = function () {

    /**
     * Scroll-up will be shown after sroll reaches this value
     * @type {Number}
     */
    var offsetToShow = 300;

    /**
     * Size content width
     * @type {Number}
     */
    var layoutWidth = 0;

    /**
     * Clickable Element that holds arrow
     * @type {Element}
     */
    var clickableZone = null;

    /**
     * Window Resize stop watcher Timeout
     * @type {timeoutId}
     */
    var resizeStopWatcher = null;

    /**
     * @public
     *
     * Init method
     * Fired after document is ready
     */
    var init = function init(layoutHolderId) {

        var layout = document.getElementById(layoutHolderId);

        if (!layout) {

            codex.core.log('Layout center-col ID wissed', 'scrollUp', 'warn');
            return;
        }

        layoutWidth = layout.offsetWidth;

        clickableZone = makeUI();

        /** Bind click event on scroll-up button */
        clickableZone.addEventListener('click', scrollPage);

        /** Global window scroll handler */
        window.addEventListener('scroll', windowScrollHandler);

        /** Autoresize */
        window.addEventListener('resize', sizeChanged, false);

        /** Set size */
        resize();

        /* Check heigth */
        windowScrollHandler();
    };

    /**
    * Scroll the document to the begin position
    * @param {number} yCoords Y-coordinate
    */
    var scrollPage = function scrollPage(yCoords) {

        window.scrollTo(0, yCoords);
    };

    /**
    * Hiding Scroll-up button if user on the top of page
    */
    var windowScrollHandler = function windowScrollHandler() {

        var notTheTop = window.pageYOffset > offsetToShow;

        if (notTheTop) {

            clickableZone.classList.add('show');
        } else {

            clickableZone.classList.remove('show');
        }
    };

    /**
    * Resize hover/click area touching user width of screen
    */
    var resize = function resize() {

        var windowWidth = document.body.clientWidth,
            leftColumtWidth = (windowWidth - layoutWidth) / 2;

        clickableZone.style.width = leftColumtWidth + 'px';
    };

    /**
    * Delay for resize
    */
    var sizeChanged = function sizeChanged() {

        if (resizeStopWatcher) {

            window.clearTimeout(resizeStopWatcher);
        }

        resizeStopWatcher = window.setTimeout(resize, 150);
    };

    /**
     * Makes scroll-up arrow and wrapper
     */
    var makeUI = function makeUI() {

        var wrapper = document.createElement('DIV'),
            arrow = document.createElement('DIV');

        wrapper.classList.add('scroll-up');
        arrow.classList.add('scroll-up__arrow');

        wrapper.appendChild(arrow);
        document.body.appendChild(wrapper);

        return wrapper;
    };

    return {
        init: init
    };
}();

/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Admin module
 */

module.exports = function () {

    var emptyBrandingClass = 'branding--empty';
    var loadingClass = 'branding--loading';
    var preloaderClass = 'branding__preloader';
    var preloadShown = 'branding__preloader--shown';

    /**
     * Branding holder
     * @type {Element|null}
     */
    var wrapper = null;

    /**
     * Initialization
     * @fires preload
     */
    var init = function init() {

        wrapper = document.getElementById('brandingSection');

        if (!wrapper) {

            return;
        }

        var url = wrapper.dataset.src;

        preload(url);
    };

    /**
     * Shows blurred preview and change it to the full-view image
     * @param  {String} fullUrl          - URL of original image
     * @return {String|null} previewUrl  - pass to renew preloader
     */
    var preload = function preload(fullUrl, previewUrl) {

        var preloader = wrapper.querySelector('.' + preloaderClass),
            img = document.createElement('IMG');

        if (previewUrl) {

            preloader.style.backgroundImage = "url('" + previewUrl + "')";
            preloader.classList.add(preloadShown);
        }

        img.src = fullUrl;
        img.onload = function () {

            wrapper.style.backgroundImage = "url('" + fullUrl + "')";
            preloader.classList.remove(preloadShown);
        };
    };

    /**
     * changes site branding
     * @private
     */
    var change = function change() {

        codex.transport.init({

            url: '/upload/4',
            accept: 'image/*',
            beforeSend: function beforeSend() {

                wrapper.classList.add(loadingClass);
            },
            success: function success(result) {

                var response = JSON.parse(result),
                    url,
                    preview;

                wrapper.classList.remove(loadingClass);

                if (response.success) {

                    url = response.data.url;
                    preview = '/upload/branding/preload_' + response.data.name + '.jpg';

                    if (wrapper.classList.contains(emptyBrandingClass)) {

                        wrapper.classList.remove(emptyBrandingClass);
                    }

                    preload(url, preview);
                } else {

                    codex.alerts.show({
                        type: 'error',
                        message: 'Uploading failed'
                    });
                }
            },
            error: function error() {

                wrapper.classList.remove(loadingClass);

                codex.alerts.show({
                    type: 'error',
                    message: 'Error while uploading branding image;'
                });
            }

        });
    };

    return {
        init: init,
        change: change
    };
}({});

/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Page significant methods
 */
module.exports = function () {

    /**
     * Page cover module
     */
    var cover = __webpack_require__(23);

    /**
     * Page pin module
     */
    var pin = __webpack_require__(24);

    /**
     * Saves current clicked item in page drop-down menu
     * @type {Element}
     */
    var currentItemClicked = null;

    /**
     * Opens page-writing form
     */
    var openWriting = function openWriting() {

        currentItemClicked = this;

        var targetId = currentItemClicked.dataset.id;

        document.location = '/p/writing?id=' + targetId;
    };

    /**
     * Opens page-writing form
     */
    var remove = function remove() {

        currentItemClicked = this;

        var targetId = currentItemClicked.dataset.id;

        if (!window.confirm('Подтвердите удаление страницы')) {

            return;
        }

        codex.ajax.call({
            url: '/p/' + targetId + '/delete',
            success: removeHandler
        });
    };

    /**
     * Opens writing form for child page
     */
    var newChild = function newChild() {

        currentItemClicked = this;

        var targetId = currentItemClicked.dataset.id;

        document.location = '/p/writing?parent=' + targetId;
    };

    /**
     * Send ajax request to add page to menu
     */
    var addToMenu = function addToMenu() {

        currentItemClicked = this;
        currentItemClicked.classList.add('loading');

        var targetId = currentItemClicked.dataset.id;

        codex.ajax.call({
            url: '/p/' + targetId + '/promote?list=4',
            success: promote
        });
    };

    /**
     * Send ajax request to add page to main
     */
    var addToMain = function addToMain() {

        currentItemClicked = this;
        currentItemClicked.classList.add('loading');

        var targetId = currentItemClicked.dataset.id;

        codex.ajax.call({
            url: '/p/' + targetId + '/promote?list=3',
            success: promote
        });
    };

    /**
     * Parse JSON response
     * @param {JSON} response
     * @returns {Object} response
     */
    var getResponse = function getResponse(response) {

        try {

            response = JSON.parse(response);
        } catch (e) {

            return {
                success: 0,
                message: 'Произошла ошибка, попробуйте позже'
            };
        }

        return response;
    };

    /**
     * Response handler for page remove
     * @param response
     */
    var removeHandler = function removeHandler(response) {

        response = getResponse(response);

        if (response.success) {

            window.location.replace(response.redirect);
            return;
        }

        codex.alerts.show({
            type: 'error',
            message: response.message
        });
    };

    /**
     * Response handler for page promotion
     * @param response
     */
    var promote = function promote(response) {

        response = getResponse(response);
        currentItemClicked.classList.remove('loading');

        if (response.success) {

            if (response.buttonText) {

                replaceMenu(currentItemClicked, response.buttonText);
            }

            if (response.menu) {

                updateSiteMenu(response.menu);
            }

            /**
             * TODO: сделать замену текста кнопки
             **/

            codex.alerts.show({
                type: 'success',
                message: response.message
            });

            return;
        }

        codex.alerts.show({
            type: 'error',
            message: response.message
        });
    };

    /**
     * Replace site menu with new button text from server response
     * @param currentItemMenu
     * @param newResponseMenuText
     */
    var replaceMenu = function replaceMenu(currentItemMenu, newResponseMenuText) {

        var itemIndex = currentItemMenu.dataset.itemIndex,
            menuIndex = currentItemMenu.dataset.index;

        /** update item on menu */
        codex.islandSettings.updateItem(menuIndex, itemIndex, newResponseMenuText);

        /** update item text immediatelly */
        currentItemMenu.textContent = newResponseMenuText;
    };

    /**
     * Replace site menu with menu form server response
     *
     * @param menu
     */
    var updateSiteMenu = function updateSiteMenu(menu) {

        var oldMenu = document.getElementById('js-site-menu'),
            newMenu = codex.core.parseHTML(menu)[0];

        codex.core.replace(oldMenu, newMenu);
    };

    return {
        openWriting: openWriting,
        newChild: newChild,
        addToMenu: addToMenu,
        addToMain: addToMain,
        remove: remove,
        pin: pin,
        cover: cover
    };
}();

/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Actions with page cover
 */
module.exports = function (cover) {

    var css = {
        cover: 'posts-list-item__cover',
        setCover: 'posts-list-item__cover--empty',
        setCoverShowed: 'posts-list-item__cover--empty-showed',
        preview: 'posts-list-item__cover--preview'
    };

    /**
     * Transport type constant
     * @type {Number}
     */
    var TRANSPORT_PAGE_COVER = 5;

    /**
     * Menu 'set-cover' button handler
     * @this menu Element
     */
    cover.toggleButton = function () {

        var pageId = this.dataset.id,
            pageIsland,
            setCoverButton;

        pageIsland = document.getElementById('js-page-' + pageId);

        if (!pageIsland) {

            return;
        }

        setCoverButton = pageIsland.querySelector('.' + css.cover);

        setCoverButton.classList.add(css.setCover);
        setCoverButton.classList.toggle(css.setCoverShowed);

        /** Let user see cover-button, than click on it */
        window.setTimeout(function () {

            cover.set(pageId);
        }, 300);
    };

    /**
     * Select file
     * @param {Number} pageId - cover's page id
     */
    cover.set = function (pageId) {

        if (isNaN(pageId)) {

            codex.core.log('Wrong pageId passed %o', '[page.cover]', 'warn', pageId);
            return;
        }

        codex.transport.init({
            url: '/upload/' + TRANSPORT_PAGE_COVER,
            data: {
                target: pageId
            },
            success: uploaded,
            beforeSend: beforeSend.bind(pageId),
            error: error
        });
    };

    /**
     * Makes preview
     * @this {pageId}
     */
    function beforeSend() {

        var pageId = this,
            article = document.getElementById('js-page-' + pageId),
            coverHolder;

        if (!article) {

            return;
        }

        coverHolder = article.querySelector('.' + css.cover);

        /** Compose preview */
        makePreview(coverHolder, pageId);
    }

    /**
     * Makes uploading image preview
     * @param  {Element} holder cover holder image
     * @param  {string|Number} pageId
     */
    function makePreview(holder, pageId) {

        var input = codex.transport.input,
            files = input.files,
            reader;

        console.assert(files, 'There is no files in input');

        reader = new FileReader();
        reader.readAsDataURL(files[0]);

        holder.classList.add(css.preivew);

        reader.onload = function (e) {

            updateCoverImage({
                url: e.target.result,
                target: pageId
            }, holder, true // is preview mode
            );
        };
    }

    /**
     * Success uploading handler
     * @param  {String} response    server answer
     */
    function uploaded(response) {

        response = JSON.parse(response);

        if (!response.success) {

            codex.alerts.show({
                type: 'error',
                message: response.message || 'File uploading error :('
            });

            return;
        }

        console.assert(response.data && response.data.url, 'Wrong response data');

        update(response.data);
    }

    /**
     * Update cover after succes uploading
     * Do it after new image loading
     * @param  {Object} imageData  - new cover data
     */
    function update(imageData) {

        var img = document.createElement('IMG');

        /** Wait for browser download and cache image */
        img.onload = function () {

            updateCoverImage(imageData);
        };

        img.src = imageData.url;
    }

    /**
     * Updates visible cover image
     * @param  {Object} imageData
     * @param  {string} imageData.url       - full cover URL
     * @param  {string} imageData.target    - page id
     * @param  {Element|null} coverHolder   - page cover holder (if known)
     * @param  {Boolean} isPreview          - pass TRUE for preview-mode
     */
    function updateCoverImage(imageData, coverHolder, isPreview) {

        console.assert(imageData.target, 'Page id must be passed as target');

        var article = document.getElementById('js-page-' + imageData.target);

        if (!article) {

            return;
        }

        coverHolder = coverHolder || article.querySelector('.' + css.cover);

        if (!coverHolder) {

            codex.core.log('Nothing to update. Cover was not found', '[page.cover]', 'warn');
            return;
        }

        if (isPreview) {

            coverHolder.classList.add(css.preview);
        } else {

            coverHolder.classList.remove(css.preview);
        }

        /** Remove button svg icon */
        coverHolder.innerHTML = '';

        coverHolder.style.backgroundImage = 'url(' + imageData.url + ')';
        coverHolder.classList.remove(css.setCover, css.setCoverShowed);
    }

    /**
     * Uploading error
     * @param  {Object} error
     */
    var error = function error(uploadError) {

        codex.core.log('Cover uploading error: %o', '[pages.cover]', 'warn', uploadError);
    };

    return cover;
}({});

/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Pins page in news feed
 */
module.exports = function () {

    var currentItemClicked = null,
        targetId = null;

    /**
     * Requests page pin toggle via ajax
     */
    var toggle = function toggle() {

        currentItemClicked = this;
        targetId = currentItemClicked.dataset.id;

        currentItemClicked.classList.add('loading');

        codex.ajax.call({
            url: '/p/' + targetId + '/pin',
            success: success,
            error: error
        });
    };

    /** Response for success ajax request **/
    var success = function success(response) {

        currentItemClicked.classList.remove('loading');
        response = JSON.parse(response);

        codex.alerts.show({
            type: response.success ? 'success' : 'error',
            message: response.message
        });

        var page = document.getElementById('js-page-' + targetId);

        var time = page.querySelector('.js-article-time');

        time.textContent = response.message;
    };

    /** Response for ajax request errors **/
    var error = function error() {

        currentItemClicked.classList.remove('loading');

        codex.alerts.show({
            type: 'error',
            message: 'Произошла ошибка'
        });
    };

    return {
        toggle: toggle
    };
}();

/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 *  Module for custom checkboxes
 *
 *  Adds stylized checkbox. You can use them in forms as default checkboxes.
 *  You can user your own styles, just change classes names in classes dictionary
 *
 *  To init module
 *  1. Add element with `cdx-custom-checkbox` name to page.
 *     You can have any other elements in it, checkbox will be added as first child.
 *
 *     `cdx-custom-checkbox` element can have data attributes:
 *      - data-name -- name of checkbox input, that will be in request array.
 *                    By default uses NAMES.defaultInput ('cdx-custom-checkbox')
 *
 *     - data-checked -- if TRUE checkbox will be checked by default
 *
 *      Example:
 *      <span name='cdx-custom-checkbox' data-name='checkbox1' data-checked='false'>I agree</span>
 *
 *  2. Call `init` method of this module
 *  3. If you want to handle changing of checkbox just register event listener on `cdx-custom-checkbox` element with 'toggle' event type:
 *
 *     checkbox.addEventListener('toggle', handler)
 *
 *  @requires checkboxes.css
 *
 *  @author gohabereg
 *  @version 1.0
 */

module.exports = function () {

    /**
     * Custom event for checkboxes. Dispatches when checkbox clicked
     * @type {CustomEvent}
     */
    var ToggleEvent = new window.CustomEvent('toggle'),


    /**
     * Elements classes dictionary
     */
    CLASSES = {
        wrapper: 'cdx-checkbox',
        checkbox: 'cdx-checkbox__slider',
        checked: 'cdx-checkbox--checked',
        defaultCheckbox: 'cdx-default-checkbox--hidden'
    },

    /**
     * Elements names dictionary
     */
    NAMES = {
        checkbox: 'cdx-custom-checkbox',
        defaultInput: 'cdx-custom-checkbox'
    };

    /**
     * Creates checkbox element in wrapper with `cdx-custom-checkbox` name
     *
     * @param wrapper - element with `cdx-custom-checkbox` name
     */
    var prepareCheckbox = function prepareCheckbox(wrapper) {

        var input = document.createElement('INPUT'),
            checkbox = document.createElement('SPAN'),
            firstChild = wrapper.firstChild;

        input.type = 'checkbox';
        input.name = wrapper.dataset.name || NAMES.defaultInput;
        input.value = 1;
        input.classList.add(CLASSES.defaultCheckbox);

        checkbox.classList.add(CLASSES.checkbox);
        checkbox.appendChild(input);

        wrapper.classList.add(CLASSES.wrapper);
        wrapper.addEventListener('click', clicked);

        if (wrapper.dataset.checked) {

            input.checked = true;

            wrapper.classList.add(CLASSES.checked);
        }

        if (firstChild) {

            wrapper.insertBefore(checkbox, firstChild);
        } else {

            wrapper.appendChild(checkbox);
        }
    };

    /**
     * Handler for click event on checkbox. Toggle checkbox state and dispatch CheckEvent
     */
    var clicked = function clicked() {

        var wrapper = this,
            checkbox = wrapper.querySelector('.' + CLASSES.checkbox),
            input = checkbox.querySelector('input');

        checkbox.parentNode.classList.toggle(CLASSES.checked);
        input.checked = !input.checked;

        /**
         * Add `checked` property to CheckEvent
         */
        ToggleEvent.checked = input.checked;

        checkbox.dispatchEvent(ToggleEvent);
    };

    /**
     * Takes all elements with `cdx-custom-checkbox` name and calls prepareCheckbox function for each one
     */
    var init = function init() {

        var checkboxes = document.getElementsByName(NAMES.checkbox);

        Array.prototype.forEach.call(checkboxes, prepareCheckbox);
    };

    return {
        init: init
    };
}();

/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function () {

    var change = function change(e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        var wrapper = this.parentNode;

        codex.transport.init({

            url: '/upload/7',
            accept: 'image/*',
            success: function success(result) {

                var response = JSON.parse(result),
                    img = document.getElementById('js-site-logo');

                if (response.success) {

                    if (!img) {

                        img = document.createElement('IMG');
                        wrapper.appendChild(img);
                    }

                    img.src = response.data.url;
                    wrapper.classList.remove('site-head__logo--empty');
                } else {

                    codex.alerts.show({
                        type: 'error',
                        message: 'Uploading failed'
                    });
                }
            },
            error: function error() {

                codex.alerts.show({
                    type: 'error',
                    message: 'Error while uploading logo'
                });
            }

        });
    };

    return {
        change: change
    };
}();

/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(module) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

!function (e, o) {
  "object" == ( false ? "undefined" : _typeof(exports)) && "object" == ( false ? "undefined" : _typeof(module)) ? module.exports = o() :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (o),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) ? exports.codexSpecial = o() : e.codexSpecial = o();
}(undefined, function () {
  return function (e) {
    function o(i) {
      if (t[i]) return t[i].exports;var a = t[i] = { exports: {}, id: i, loaded: !1 };return e[i].call(a.exports, a, a.exports, o), a.loaded = !0, a.exports;
    }var t = {};return o.m = e, o.c = t, o.p = "", o(0);
  }([function (e, o, t) {
    e.exports = t(1);
  }, function (e, o, t) {
    e.exports = function () {
      function e(e) {
        for (var o in e) {
          g[o] = e[o];
        }
      }function o() {
        var e = document.createElement("STYLE"),
            o = t(4);e.innerHTML = o[0][1], document.head.appendChild(e);
      }function i() {
        b = d[g.lang];var e = x.toolbar(),
            o = x.textSizeSwitcher();e.appendChild(o);for (var t in p.colorSwitchers) {
          var i = x.colorSwitcher(t);i.dataset.style = t, e.appendChild(i), f.colorSwitchers.push(i);
        }f.toolbar = e, f.textSizeSwitcher = o, a();
      }function a() {
        if (g.blockId) {
          var e = document.getElementById(g.blockId);if (e) return e.appendChild(f.toolbar), void f.toolbar.classList.add(p.toolbar.state.included);g.blockId = "";
        }if (document.body.classList.add(p.body.excludeModificator), f.toolbar.classList.add(p.toolbar.state.excluded), g.position) switch (g.position) {case "top-left":
            f.toolbar.classList.add(p.toolbar.position.top, p.toolbar.position.left);break;case "bottom-right":
            f.toolbar.classList.add(p.toolbar.position.bottom, p.toolbar.position.right);break;case "bottom-left":
            f.toolbar.classList.add(p.toolbar.position.bottom, p.toolbar.position.left);break;default:
            f.toolbar.classList.add(p.toolbar.position.top, p.toolbar.position.right);}document.body.appendChild(f.toolbar);
      }function c() {
        f.colorSwitchers.map(function (e, o) {
          e.addEventListener("click", A, !1);
        }), f.textSizeSwitcher.addEventListener("click", n, !1);
      }function r() {
        var e,
            o = window.localStorage.getItem(m.color),
            t = window.localStorage.getItem(m.textSize);o && f.colorSwitchers.map(function (e, t) {
          e.dataset.style == o && A.call(e);
        }), t && (e = f.textSizeSwitcher, n.call(e));
      }function A() {
        return this.classList.contains(p.circle.state.enabled) ? l() : (l(), f.colorSwitchers.map(function (e, o) {
          e.classList.add(p.circle.state.disabled);
        }), this.classList.remove(p.circle.state.disabled), this.classList.add(p.circle.state.enabled), window.localStorage.setItem(m.color, this.dataset.style), void document.body.classList.add(p.colorSwitchers[this.dataset.style]));
      }function l() {
        for (var e in p.colorSwitchers) {
          document.body.classList.remove(p.colorSwitchers[e]);
        }f.colorSwitchers.map(function (e, o) {
          e.classList.remove(p.circle.state.disabled, p.circle.state.enabled);
        }), window.localStorage.removeItem(m.color);
      }function n() {
        return document.body.classList.contains(p.textSizeIncreased) ? s() : (s(), f.textSizeSwitcher.innerHTML = '<i class="' + p.iconButton.elem + '"></i> ' + b.decreaseSize, window.localStorage.setItem(m.textSize, "big"), void document.body.classList.add(p.textSizeIncreased));
      }function s() {
        document.body.classList.remove(p.textSizeIncreased), f.textSizeSwitcher.innerHTML = '<i class="' + p.iconButton.elem + '"></i> ' + b.increaseSize, window.localStorage.removeItem(m.textSize);
      }var d = t(2),
          p = t(3),
          b = null,
          f = { toolbar: null, colorSwitchers: [], textSizeSwitcher: null },
          m = { textSise: "codex-special__text-size", color: "codex-special__color" },
          g = { blockId: null, lang: "ru", position: "top-right" },
          h = function h() {};h.prototype.init = function (o) {
        e(o), i(), c(), r();
      };var x = { element: function element(e, o) {
          var t = document.createElement(e);return t.classList.add(o), t;
        }, toolbar: function toolbar() {
          return x.element("DIV", p.toolbar.elem);
        }, colorSwitcher: function colorSwitcher(e) {
          var o = x.element("SPAN", p.circle.elem);return o.classList.add(p.circle.prefix + e), o;
        }, textSizeSwitcher: function textSizeSwitcher() {
          var e = x.element("SPAN", p.textButton.elem);return e.innerHTML = '<i class="' + p.iconButton.elem + '"></i> ' + b.increaseSize, e;
        } };return o(), new h();
    }();
  }, function (e, o) {
    e.exports = { ru: { increaseSize: "Увеличить шрифт", decreaseSize: "Уменьшить шрифт" }, en: { increaseSize: "Increase font", decreaseSize: "Decrease font" } };
  }, function (e, o) {
    e.exports = { colorSwitchers: { blue: "special-blue", green: "special-green", white: "special-white" }, textSizeIncreased: "special-big", toolbar: { elem: "codex-special__toolbar", state: { included: "codex-special__toolbar--included", excluded: "codex-special__toolbar--excluded" }, position: { top: "codex-special__toolbar--position-top", bottom: "codex-special__toolbar--position-bottom", left: "codex-special__toolbar--position-left", right: "codex-special__toolbar--position-right" } }, iconButton: { elem: "codex-special__toolbar-icon" }, textButton: { elem: "codex-special__toolbar-text" }, circle: { elem: "codex-special__circle", prefix: "codex-special__circle--", state: { enabled: "codex-special__circle--enabled", disabled: "codex-special__circle--disabled" } }, body: { excludeModificator: "codex-special--excluded" } };
  }, function (e, o, t) {
    o = e.exports = t(5)(), o.push([e.id, '.special-blue article,.special-blue aside,.special-blue div,.special-blue footer,.special-blue form,.special-blue header,body.special-blue{background-color:#c1d1e6!important;background-image:none!important;color:#3f5077!important}.special-blue *{box-shadow:none;border-color:hsla(0,0%,53%,.24)!important}.special-blue input,.special-blue textarea{background-color:#d9e4f7!important;color:#36465b!important}.special-blue a{color:#082a90!important}.special-blue a:hover{color:#0034d2!important}.special-green article,.special-green aside,.special-green div,.special-green footer,.special-green form,.special-green header,body.special-green{background-color:#2b2924!important;background-image:none!important;color:#33d24a!important}.special-green *{box-shadow:none;border-color:hsla(0,0%,53%,.24)!important}.special-green input,.special-green textarea{background-color:#211f1a!important;color:#44e963!important}.special-green a{color:#98ff81!important}.special-green a:hover{color:#c8ffbb!important}.special-white article,.special-white aside,.special-white div,.special-white footer,.special-white form,.special-white header,body.special-white{background-color:#121213!important;background-image:none!important;color:#666871!important}.special-white *{border-color:#212121!important;box-shadow:none}.special-white input,.special-white textarea{background-color:#1d1d1d!important;color:#fff!important}.special-white a{color:#9ea0ad!important}.special-white a:hover{color:#fff!important}.special-big div{font-size:20px;line-height:1.5em}.special-big div *{font-size:inherit!important;line-height:inherit}.special-big h1{font-size:2em!important;line-height:1.35em}.special-big h2{font-size:1.5em!important;line-height:1.35em}.special-big h3{font-size:1.17em!important;line-height:1.35em}.special-big h4{font-size:1.12em!important;line-height:1.35em}.special-big h5{font-size:.83em!important;line-height:1.35em}.special-big h6{font-size:.75em!important;line-height:1.35em}@font-face{font-family:codex-special;src:url(data:application/font-woff2;charset=utf-8;base64,d09GMgABAAAAAAZYABEAAAAADFgAAAXzAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP0ZGVE0cGiAGVgCCaggMCYRlEQgKhDiEDQE2AiQDTAsoAAQgBYRsB4FGDFo/d2ViZgYb2QpRVIymSPYjwcYtrnfNxVJ/+U+4We9HUKnRXoemTmrCKjVnhW6yppITtwnnJlxuI8UPBdPrLCGep6gHwNFaq28WP5R/2Ib1eDt3PofJomIt6/4jIhEvmVLImZZYYle7BK78fBEAAoBnx1/2AgC8klmeAoBX5bfKSm/mAZADABBAIARAkFK3AUi8kE6BDEkOyOcAdJz/8+LrWCj4AAGyCCNacnHXQiTQVvKvja62AeUoqgCIdnJ+zroQiSuQ0IvnN2wQYAHQzhyshs0p+TeTAYA2Bjgg8/CH8gcffZB+O2tU1VhFlqmySdjK2Molgv7LRQnUayQFYC7OEcChcgQH+GBS4/2bdQ5lYg63xLTxev5xu3T2KqdgdU4VJ5W9zV2xKi6oA5O1LkWPUjuI6rIcxhNJLerU61VcUrtxokp0u/Ze5Dk90uZE7b/Id3pEiXI5pvHuzXqhYNCx+LLqnjm9iltUs4iOztyWHZ05M8IxRIVVNYuz7kRkmxrws3hNFberbNcE+qPkWoyLJX0K41JpP0dSMxYZ81jQsTUSjeoRI+WYDJOZDmRlvogvOlqFU2Uvr0VzwS1quDU2U2OsR+met5xpbOH87tDGcM9K2ajMYD1G9zzFYIYylaEMnM4TPN0aZPJ5YlGCtkfPLH3Hv54TjUbY68Ye0A5NfYwjhQujTZq8qsJen5irMG1gSiTKSdeM1bTuUwyFGX2OMa+xxIOXX8V9Z2ANGP7FQzBfAAzD0xR2ztxNAMuCKkNuHNkzbv9CxbByltTGRR4sPTGk3oIEJdrbaeBOHxbAhYYZKQ04kzVlPuNQ2iOshTQdjkhM1tJg6FjQniZG0J2zBTx3Uf48LKzyEqZwOCdVgUCZjBcAEGRPKQUgA9gKgMOIIwE4DfMLL+UYuDx1OVz5RPsK6eo3nnjmgcIdznPDs157K3Wj9WpmybWvlVyIHONjh/Fh477CaWVnZ2effTbte310dPWrb+6977Xbmrr2Ym/l/3mr49Udqdecx7e8PXrBVPtbv8375I8Zb/8+/8Nf/9o3ccni+/e9cfz4RUseOP61veMWLrpv76vGhMUL7zUoacmtvWOS+543Pvit7PH1F52a/d9I2Zxb7qjblLvMq/RsKlx3rRLek1MwGPcPz055f2qOP1IRfmXKXZtmOUIDj9VtadhWQYVdOyaNCfeF33mlKafWEe4tyO51vCUs8BYdKL1a3EXdOS8ta9ny2y0zforDfktb2y2OZmdX2W+JWxcuWLw4pZSYbVOLB4YWbyoxhNAy5EgFB4aWFi7VATukRSCWzi2ON1BJTYhfpfOLxtfz0l1AzKyTvko7gmsSH6kxkxSD6ejyB/hgvLYOaXvtd4aEr3hOPS+rIV5ew/G66Za/4v567vaZjGI8r960yF+ZFRQz3fAHTCfF45z503ZvQZzmWQKmbI3HYdpDFR6bgw5aQIA9uMsZmOMd95ut8H8g/n1G+7rNFD3pNhgUZWTedKWi8b/WZlCvBITrWlBuPUg+lHcRAFDKtqLxXADrCJBC8UsIRUGMzMwEkIpZRcqhnuXBTRBBkh1AJRAzQh4vARHgQScRkYfJREIlVhIZWTiLWJCHG4gDo3icOFFK+eRJZFE7eQq1pJGn4aGj1mfk8TxviUdS+wrEF37gC/gMyA9o8gu2rqztQVcW1n3yCQL0pT2ecp61Nywet5lk6XzQH+vZ2NnzG7uArmh9J7mBtufTGpwATICtH2rDDOG40FKfzIYVtnIcxiy4lE23vbZApecTbVHBwuYGUkmNgjM5Qq0XBLcS9BVmBQTckGGCrhF0tlQg8OZhPklh/HYVlDBS3X73Ch4YlM/hIkLiWyMoAUkgEcUoIYlkspCVbGQnBznJRW7ykJd85LcsWblt7dI6r3W9ddPqZfW1huw765QyZ4Oz0dnkbHa2OFudcUdboL7bbAKBuV7HnZQ5xOlEDHBbUjOJTtLN7n9yW+7TB3hoclC3Vy/gln/ZDcAF1sXFWCeXWFdasAqxhFUMSOFC4nJ1BJ2HYqBpH5ZfDQAAAA==) format("woff2"),url(data:application/font-woff;charset=utf-8;base64,d09GRgABAAAAAAisABEAAAAADFgAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAABGRlRNAAABgAAAABwAAAAcdELqwEdERUYAAAGcAAAAHQAAACAAQAAET1MvMgAAAbwAAAA+AAAAVmOXkc9jbWFwAAAB/AAAAFsAAAFqUKw+iGN2dCAAAAJYAAAADAAAAAwCmAKwZnBnbQAAAmQAAAGxAAACZVO0L6dnYXNwAAAEGAAAAAgAAAAIAAAAEGdseWYAAAQgAAAB5wAAAjgvlXr5aGVhZAAABggAAAApAAAANg89/0toaGVhAAAGNAAAABkAAAAkEAIH7GhtdHgAAAZQAAAAKwAAAExEiQAAbG9jYQAABnwAAAARAAAAKAgiCKhtYXhwAAAGkAAAACAAAAAgAS0ArG5hbWUAAAawAAABLgAAAmwQW2PtcG9zdAAAB+AAAABqAAAAxhjhRm9wcmVwAAAITAAAAFYAAABaiarBv3dlYmYAAAikAAAABgAAAAYoqFfgAAAAAQAAAADMPaLPAAAAANP/bskAAAAA1AXZJ3jaY2BkYGDgA2IJBhBgYmAEQiEgZgHzGAAFEABFAAAAeNpjYGSeyziBgZWBhVWIdQYDA6MchGa+xpDCJMDAwMTAysyAFQSkuaYwODA4qv7hAPOBpAaQYgSxAWc1B1YAAHjaY2BgYGaAYBkGRgYQSAHyGMF8FgYPIM3HwMHAxMAGZDkpcCnoK8Sr/vn/H6zSUYEBwf9/6AHr/Yf3N90Sg5qDBBiBumGCjExAggldAQNhwMzCMJQBAFjUELYAAAAAUAIAAGAAmAIAeNpdUbtOW0EQ3Q0PA4HE2CA52hSzmZDGe6EFCcTVjWJkO4XlCGk3cpGLcQEfQIFEDdqvGaChpEibBiEXSHxCPiESM2uIojQ7O7NzzpkzS8qRqnfpa89T5ySQwt0GzTb9Tki1swD3pOvrjYy0gwdabGb0ynX7/gsGm9GUO2oA5T1vKQ8ZTTuBWrSn/tH8Cob7/B/zOxi0NNP01DoJ6SEE5ptxS4PvGc26yw/6gtXhYjAwpJim4i4/plL+tzTnasuwtZHRvIMzEfnJNEBTa20Emv7UIdXzcRRLkMumsTaYmLL+JBPBhcl0VVO1zPjawV2ys+hggyrNgQfYw1Z5DB4ODyYU0rckyiwNEfZiq8QIEZMcCjnl3Mn+pED5SBLGvElKO+OGtQbGkdfAoDZPs/88m01tbx3C+FkcwXe/GUs6+MiG2hgRYjtiKYAJREJGVfmGGs+9LAbkUvvPQJSA5fGPf50ItO7YRDyXtXUOMVYIen7b3PLLirtWuc6LQndvqmqo0inN+17OvscDnh4Lw0FjwZvP+/5Kgfo8LK40aA4EQ3o3ev+iteqIq7wXPrIn07+xWgAAAAABAAH//wAPeNpVUDFv01AQvntxnu3XJCSu6xhUQuS6LyGREsdxEowglSpaRZUVIjYkhBhQVHVkQBWq+CEMzKgD8rMyd0L9CR0qJrZsiKETeeIFFnp30n1333fDfUAAYGaAnsEzmEIMWQ6gnd31436/LyC3zLa9p2EYZgdqLXraMmMKYPq8K2bYBsEOKlaaxEFvEI0GY22Efae66VjVdVVrWHW2dM/f8TQ92iPDPRzjIOJd3KXErpEHaG/Zf2m6Q5W0H45Ig+pk+8WTcadMzj4zIpuL0DrxXcs9twySyMvpa3keaN+sEi0YNAmj1VX9zDvqdCIykT8mrEPekuaXj4du5+S08GpGW4FRKrgBp+8sY5W+kZP3LflzMixbPg3cmlUmw1aLahqjJWZfnn4gh/LrY+4UNOoGzHFZOcc1QvDRjf9L2QRrr8BQCRvQgJeQmWtTbLJM3TBtdDFtdlO4EsX8Mq2EabEs6thO74WC5pfiIbZFESqW2MA4TuuVzLxzP1aIWiKvxzEI01akwWLlZcWCvA+bBHY1A6ORiTwahlUTh6Fj6yY6NvUaJlKPkX1c4ILsM7a6kIlMVhd4w9GX33kybyJHzudTLq9VT45Vl9f8WIO1jN06/f1pzm+pp/N/ajWjQly9jfBf9OAPEG+E1gB42mNgZGBgAGIBV26heH6brwzyHAwgcIX1pjoyzcHAAaGYQBQAt6AGBwAAAHjaY2BkYOBg+H8DRIIAkGRkQAXCADk1AgUAAAB42uNggACmVQwMHAwQzIJGg+SYgDRjKBSD2DOBRCGEBskxv2BgAACBxwTKAHjaY2CAgmmMInigDAAk3gHaAAAAAAEAAAATAE8AAgAAAAAAAgABAAIAFgAAAQAAWQAAAAB42n2QTU4CQRCFvxY0UROWLIwLVi5MRBiNiS6NkZUbTdAt6IxgwBlnRiM38ASewCXxGMafE3gb3zQlCxJIp3u+elVd/WqACu+UcOVVYFt7wo4NRRNeUs2JcUn6uXFZFQPjZaq8Gq+oZmy8RpsP43W23KbxJ1V3bPxFw10Zf1NxL8Y/Yuv5q3fdGy1C7rVTOuT63lCjy0hnS05GJPS8nnJNrGzIMztk0kMpfd0ayH3ILY+izsK6+ZnaTI+2fzFTNpa7Gk3qNBbcnz9FxpP6BlJzIsWRamKGolPfu6geaMVSEp+7831zvdjztxKO2NWKZurr3s+QSyndafbf65k5v1A28eqhP/fVreHPgD0pgaImB36uSO6LP5D72Yr5Q8XZ1GnR7UFKX7m08PEHK2teYAAAeNptyD0OgkAUReF3+UdRILAE6IeRQS0NCVsBEmNoKNy9xbt2nOZLjnii1XJcJQIPvjTSIkCICDESpDjhjAwXXJGjQBnN7++2dIqN989qjTG0o/9/oz11dKB3+qBP+lLtpDrVTeMPXIcjlwAAeNrbwfi/dQNjL4P3Bo6AiI2MjH2RG93YtCMUNwhEem8QCQIyGiJlN7Bpx0QwbGBScN3ArO2ygUXBdRMTO5M2mMMM5LCIQjiMG1ghSiI3iGgDACuyGqMAAAABV+AopwAA) format("woff");font-weight:400;font-style:normal}.codex-special__toolbar{padding:15px!important;background-color:#fff;white-space:nowrap;z-index:9999;line-height:21px!important}html body .codex-special__toolbar{font-size:15px!important}.codex-special__toolbar--included{position:relative;border-top:1px solid #f2f4f8}.codex-special__toolbar--excluded{position:fixed;width:300px;border:1px solid #f2f4f8}.codex-special__toolbar--position-top{top:0;bottom:auto;border-radius:0 0 3px 3px;border-top:0}.codex-special__toolbar--position-bottom{top:auto;bottom:0;border-radius:3px 3px 0 0;border-bottom:0}.codex-special__toolbar--position-left{left:3%;right:auto}.codex-special__toolbar--position-right{right:3%;left:auto}.codex-special__toolbar-text{display:inline-block;vertical-align:middle;cursor:pointer;border-radius:5px}.codex-special__toolbar-text:hover{color:#3d82c1}.codex-special__toolbar-icon{display:inline-block;margin-right:.3em;margin-bottom:1px;vertical-align:text-bottom;line-height:1em;font-size:.84em;font-style:normal}.codex-special__toolbar-icon:before{content:"A";font-family:codex-special}.special-big .codex-special__toolbar-icon{line-height:.96em}.special-big .codex-special__toolbar-icon:before{content:"B"}.special-big .codex-special__toolbar,.special-blue .codex-special__toolbar,.special-green .codex-special__toolbar,.special-white .codex-special__toolbar{background-color:#fff812!important;color:#15130b!important;border-color:transparent!important}.special-big .codex-special__toolbar-text:hover,.special-blue .codex-special__toolbar-text:hover,.special-green .codex-special__toolbar-text:hover,.special-white .codex-special__toolbar-text:hover{color:#b00!important}@media (max-width:1000px){.codex-special__toolbar--excluded{top:auto;bottom:0;right:0;left:0;width:auto;border:1px solid #f2f4f8}.codex-special--excluded{padding-bottom:50px}}.codex-special__circle{display:inline-block;width:7px;height:7px;border:7px solid;vertical-align:middle;box-sizing:content-box;border-radius:50%;cursor:pointer;float:right}.codex-special__circle:not(:first-of-type){margin-right:5px}.codex-special__circle:hover{width:11px;height:11px;border-width:5px}.codex-special__circle--enabled{border-radius:10%}.codex-special__circle--disabled{opacity:.7}.codex-special__circle--disabled:hover{opacity:1}.codex-special__circle--blue{background-color:#36465b;border-color:#c5dfff!important}.codex-special__circle--green{background-color:#44e963;border-color:#7c766a!important}.codex-special__circle--white{background-color:#fff;border-color:#2b2a28!important}', ""]);
  }, function (e, o) {
    e.exports = function () {
      var e = [];return e.toString = function () {
        for (var e = [], o = 0; o < this.length; o++) {
          var t = this[o];t[2] ? e.push("@media " + t[2] + "{" + t[1] + "}") : e.push(t[1]);
        }return e.join("");
      }, e.i = function (o, t) {
        "string" == typeof o && (o = [[null, o, ""]]);for (var i = {}, a = 0; a < this.length; a++) {
          var c = this[a][0];"number" == typeof c && (i[c] = !0);
        }for (a = 0; a < o.length; a++) {
          var r = o[a];"number" == typeof r[0] && i[r[0]] || (t && !r[2] ? r[2] = t : t && (r[2] = "(" + r[2] + ") and (" + t + ")"), e.push(r));
        }
      }, e;
    };
  }]);
});
//# sourceMappingURL=codex-special.min.js.map
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(28)(module)))

/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function (module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function () {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function get() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function get() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};

/***/ })
/******/ ]);
//# sourceMappingURL=bundle.js.map