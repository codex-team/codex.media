/**
* Speical contrast verison for websites
* @link https://github.com/codex-team/codex.special
* @author Codex Team — ifmo.su
*   Vitaly Guryn    https://github.com/talyguryn
*   Savchenko Petr  https://github.com/neSpecc
* @version 1.0.2
*/
var codexSpecial = (function() {

    /**
    * Multilanguage support
    */
    var DICT = {

        ru : {
            increaseSize : 'Увеличить размер',
            decreaseSize : 'Уменьшить размер'
        },

        en : {
            increaseSize : 'Increase size',
            decreaseSize : 'Decrease size'
        }

    };

    /**
    * Texts from dictionary
    */
    var texts = null;

    /**
    * @private static nodes
    */
    var nodes = {

    	toolbar : null,
        colorSwitchers   : [],
        textSizeSwitcher : null

    };

    /**
    * Required stylesheets URL
    */
    var CSS_FILE_PATH = 'codex-special.v.1.0.2.min.css';

    /**
    * Path to codex-special
    * Generated automatically
    */
    var pathToExtension;

    /**
    * @private CSS classes config
    */
  	var classes = {

        colorSwitchers : {
            blue     : 'special-blue',
            green    : 'special-green',
            white    : 'special-white',
        },

        textSizeIncreased : 'special-big'

  	};

    /**
    * Settings for codexSpecial block
    *
    * blockId — at the end of which block you want to place codexSpecial
    * scriptLocation — path to codexSpecial styles file
    * lang — language for the codexSpecial from DICT_
    */
    var initialSettings = {

        blockId : null,
        lang : 'ru',
        position : 'top-right'

    };

    /**
    * @constructor
    * Public methods and properties
    */
    var _codexSpecial = function () {};

    /**
    * Public initialization method
    * @param {Object} settings are
    *       - blockId - if passed, toolbar will be appended to this block
    *                   otherwise, it will be fixed in window
    */
    _codexSpecial.prototype.init = function (settings) {


        /**
        * 1. Save initial settings to the private property
        */
        fillSettings_(settings);

        /**
        * 2. Prepare stylesheets
        */
        loadStyles_();

        /**
        * 3. Make interface
        */
        makeUI_();

        /**
        * 4. Add listeners
        */
        addListeners_();

        /**
        * 5. Check localStorage for settings
        */
        loadSettings_();

    };


    /**
    * @private
    * Fills initialSettings
    */
    function fillSettings_(settings) {

        for (var param in settings) {

            initialSettings[param] = settings[param];

        }

        pathToExtension = getScriptLocation();

    }

    /**
    * @private
    * Gets codex-special path
    */
    function getScriptLocation() {

        var scriptsList = document.getElementsByTagName('script'),
            scriptSrc,
            lastSlashPosition;

        for (var i = 1; i < scriptsList.length; i++) {

            scriptSrc = scriptsList[i].src;

            if (scriptSrc.indexOf('codex-special') != -1) {

                lastSlashPosition = scriptSrc.lastIndexOf('/');

                scriptDir = scriptSrc.substr(0, lastSlashPosition + 1);

                return scriptDir;

            }
        }

    }

    /**
    * @private
    * Loads requeired stylesheet
    */
    function loadStyles_() {

        var style = document.createElement('link');

        style.setAttribute('type', 'text/css');
        style.setAttribute('rel', 'stylesheet');

        style.href = pathToExtension + CSS_FILE_PATH;

        document.head.appendChild( style );

    }

    /**
    * @private
    * Interface maker
    */
    function makeUI_() {

        /**
        * 0. Init dictionary
        */
        texts = DICT[initialSettings.lang];

        /**
        * 1. Make Toolbar and Switchers
        */

        var toolbar = draw_.toolbar();
        var textSizeSwitcher = draw_.textSizeSwitcher();

        /**
        * 2. Append text size switcher
        */
        toolbar.appendChild(textSizeSwitcher);

        /**
        * 3. Append color switchers
        */
        for (var color in classes.colorSwitchers) {

            circle = draw_.colorSwitcher(color);

            circle.dataset.style = color;

            toolbar.appendChild(circle);

            nodes.colorSwitchers.push(circle);

        }

        nodes.toolbar = toolbar;
        nodes.textSizeSwitcher = textSizeSwitcher;

        appendPanel_();

    }

    /**
    * @private
    * Toolbar positionin method
    */
    function appendPanel_() {

        if (initialSettings.blockId){

            document.getElementById(initialSettings.blockId).appendChild(nodes.toolbar);

            nodes.toolbar.classList.add('codex-special__toolbar_included');

            return;

        }

        nodes.toolbar.classList.add('codex-special__toolbar_excluded');

        if (initialSettings.position) {

            switch (initialSettings.position) {
                // 'top-right' is default
                case 'top-left':
                    nodes.toolbar.classList.add('codex-special__toolbar_top', 'codex-special__toolbar_left'); break;
                case 'bottom-right':
                    nodes.toolbar.classList.add('codex-special__toolbar_bottom', 'codex-special__toolbar_right'); break;
                case 'bottom-left':
                    nodes.toolbar.classList.add('codex-special__toolbar_bottom', 'codex-special__toolbar_left'); break;
                default:
                    nodes.toolbar.classList.add('codex-special__toolbar_top', 'codex-special__toolbar_right'); break;
            }
        }

        document.body.appendChild(nodes.toolbar);

    }

    /**
    * @private
    */
    function addListeners_() {

        nodes.colorSwitchers.map(function(switcher, index) {

            switcher.addEventListener('click', changeColor_, false);

        });

        nodes.textSizeSwitcher.addEventListener('click', changeTextSize_, false);

    }

    /**
    * @private
    */
    function loadSettings_() {

        var color    = localStorage.getItem('codex-special__color'),
            textSize = localStorage.getItem('codex-special__textSize'),
            textSizeSwitcher;

        if (color) {

            nodes.colorSwitchers.map(function(switcher, index) {

                if (switcher.dataset.style == color) {

                    changeColor_.call(switcher);

                }

            });

        }

        if (textSize){

            textSizeSwitcher = nodes.textSizeSwitcher;

            changeTextSize_.call(textSizeSwitcher);
        }

    }

    /**
    * @private
    */
    function changeColor_() {

        if (this.classList.contains('codex-special__circle_enabled')) {

            return dropColor_();

        }

        dropColor_();

        nodes.colorSwitchers.map(function(switcher, index) {

            switcher.classList.add('codex-special__circle_disabled');

        });

        this.classList.remove('codex-special__circle_disabled');

        this.classList.add('codex-special__circle_enabled');

        localStorage.setItem('codex-special__color', this.dataset.style);

    	document.body.classList.add(classes.colorSwitchers[this.dataset.style]);

    }

    /**
    * @private
    */
    function dropColor_() {

    	for (var color in classes.colorSwitchers){

    		document.body.classList.remove(classes.colorSwitchers[color]);

        }

        nodes.colorSwitchers.map(function(switcher, index) {

            switcher.classList.remove('codex-special__circle_disabled', 'codex-special__circle_enabled');

        });

        localStorage.removeItem('codex-special__color');

    }

    /**
    * @private
    */
    function changeTextSize_() {

        if (document.body.classList.contains(classes.textSizeIncreased)) {

            return dropTextSize_();

        }

        dropTextSize_();

        nodes.textSizeSwitcher.innerHTML = '<i class="codex-special__toolbar_icon"></i> ' + texts.decreaseSize;

        localStorage.setItem('codex-special__textSize', 'big');

        document.body.classList.add(classes.textSizeIncreased);

    }

    /**
    * @private
    */
    function dropTextSize_() {

        document.body.classList.remove(classes.textSizeIncreased);

        nodes.textSizeSwitcher.innerHTML = '<i class="codex-special__toolbar_icon"></i> ' + texts.increaseSize;

        localStorage.removeItem('codex-special__textSize');

    }

    /**
    * @private
    * HTML elements maker
    */
    var draw_ = {

        element : function (newElement, newClass) {

            var block = document.createElement(newElement);

            block.classList.add(newClass);

            return block;

        },

        /**
        * Codex special toolbar
        */
        toolbar : function () {

            return draw_.element('DIV', 'codex-special__toolbar');

        },

        /**
        * Makes color switcher
        * @param {string} type  - color string identifier
        */
        colorSwitcher : function (type) {

            var colorSwitcher = draw_.element('SPAN', 'codex-special__circle');

            colorSwitcher.classList.add('codex-special__circle_' + type);

            return colorSwitcher;

        },

        /**
        * Makes text size toggler
        */
        textSizeSwitcher : function () {

            var textToggler = draw_.element('SPAN', 'codex-special__toolbar_text');

            textToggler.innerHTML = '<i class="codex-special__toolbar_icon"></i> ' + texts.increaseSize;

            return textToggler;

        }

    };

    return new _codexSpecial();

})();
