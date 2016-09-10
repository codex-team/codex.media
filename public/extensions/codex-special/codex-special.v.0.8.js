/**
* Speical contrast verison for websites
* @author Codex Team — ifmo.su
*   Vitaly Guryn    https://github.com/illiiiillllilii
*   Savchenko Petr  https://github.com/neSpecc
* @version 0.8
*/
var codexSpecial = (function() {

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
    var CSS_FILE_PATH = 'codex-special.v.0.8.css';

    /**
    * @private CSS classes config
    */
	var classes = {
        colorSwitchers : {
            white    : 'special-white',
    		green    : 'special-green',
    		blue     : 'special-blue',
        },
        textSizeIncreased : 'special-big'
	};

    var clean = {
        color    : true,
        textSize : true,
    };

    var initialSettings = {
        blockId : null,
        scriptLocation: '/'
    };


    /**
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
        initialSettings = settings;

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

    };


    /**
    * @private
    * Loads requeired stylesheet
    */
    function loadStyles_(){

        var style = document.createElement( 'link' );

        style.setAttribute( 'type', 'text/css' );
        style.setAttribute( 'rel', 'stylesheet');

        style.href = initialSettings.scriptLocation + CSS_FILE_PATH;

        document.head.appendChild( style );

    }

    /**
    * @private
    * Interface maker
    */
    function makeUI_() {

        /**
        * 1. Make Toolbar, Hover and Switchers
        */

        var toolbar = draw_.toolbar(),
            textSizeSwitcher = draw_.textSizeSwitcher();

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
    function appendPanel_ () {

        if (initialSettings.blockId){

            document.getElementById(initialSettings.blockId).appendChild(nodes.toolbar);

            nodes.toolbar.classList.add('codex-special__included');

            return;

        }

        nodes.toolbar.classList.add('codex-special__excluded');

        document.body.appendChild(nodes.toolbar);

    }

    /**
    * @private
    */
    function addListeners_ () {

        nodes.colorSwitchers.map(function(switcher, index) {

            switcher.addEventListener('click', changeColor_, false);

        });

        nodes.textSizeSwitcher.addEventListener('click', changeTextSize_, false);

    }

    /**
    * @private
    */
    function changeColor_ () {

        dropColor_();

        if (this.style.opacity === 1 && !clean.color) {

            clear_('color');

            return;

        }

        nodes.colorSwitchers.map(function(switcher, index) {

    		switcher.style.opacity = 0.5;

        });

    	this.style.opacity = 1;

        clean.color = false;

    	document.body.classList.add(classes.colorSwitchers[this.dataset.style]);

    }

    /**
    * @private
    */
    function dropColor_ () {

    	for (var color in classes.colorSwitchers){

    		document.body.classList.remove(classes.colorSwitchers[color]);

        }

    }

    /**
    * @private
    */
    function changeTextSize_ () {

        if (!clean.textSize) {

            clear_('textSize');

            return;

        }

        nodes.textSizeSwitcher.classList.add('enabled');

        clean.textSize = false;

        document.body.classList.add(classes.textSizeIncreased);

    }

    /**
    * @private
    */
    function dropTextSize_ () {

        document.body.classList.remove(classes.textSizeIncreased);

        nodes.textSizeSwitcher.classList.remove('enabled');

    }

    /**
    * @private
    * @param param ?
    */
    function clear_ ( param ) {

        if (param == 'color') {

        	dropColor_();

            codexSpecial.colorSwitchers.map(function(switcher, index) {

                switcher.style.opacity = 1;

            });

            codexSpecial.clean.color = true;
        }

        if (param == 'textSize') {

            dropTextSize_();

            clean.textSize = true;
        }

        if (clean.color && clean.textSize){

        	nodes.hover.style.display = null;

        }

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

        hover : function () {

            return draw_.element('DIV', 'codex-special__hover');

        },

        /**
        * Makes color switcher
        * @param {String} color
        */
        colorSwitcher : function ( type ) {

            var colorSwitcher = draw_.element('SPAN', 'codex-special__circle');

            colorSwitcher.classList.add('codex-special__circle__' + type);

            return colorSwitcher;

        },

        /**
        * Makes text size toggler
        */
        textSizeSwitcher : function () {

            var textToggler = draw_.element('SPAN', 'codex-special__toolbar_text');

            textToggler.innerHTML = '<i class="codex-special__toolbar_icon icon-font"></i> Увеличенный шрифт';

            return textToggler;

        }

    };

    return new _codexSpecial();

})();

codexSpecial.init({
    // blockId : 'js-contrast-version-holder',
    scriptLocation: '/public/extensions/codex-special/'
});