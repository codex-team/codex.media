var codexSpecial = {

	body    : document.body,
	toolbar : null,
	hover   : null,

	colorSwitchers : [],

    /**
    *
    */
    textSizeSwitcher : null,

	colorSwitchersClasses : {
        white : 'special-white',
		green : 'special-green',
		blue  : 'special-blue',
	},

    textSizeClass : 'special-big',

    clean : {
        color    : true,
        textSize : true,
    },

    settings : {
        blockId : null,
    },

    init : function (settings) {

        /**
        * 1. Get settings
        */
        this.settings = settings;

        /**
        * 2. Make interface
        */
        this.makeUI();

        /**
        * 3. Add listeners
        */
        this.addListeners();

    },

    makeUI : function () {

        /**
        * 1. Make Toolbar, Hover and Switchers
        */

        var toolbar = this.draw.toolbar(),
            hover   = this.draw.hover(),
            textSizeSwitcher = this.draw.textSizeSwitcher();

        /**
        * 2. Append elements
        */

        toolbar.appendChild(hover);
        hover.appendChild(textSizeSwitcher);

        /**
        * 3. Append color switchers
        */
        for (var color in this.colorSwitchersClasses) {

            circle = this.draw.colorSwitcher(color);

            circle.dataset.style = color;

            hover.appendChild(circle);

            this.colorSwitchers.push(circle);

        }

        this.toolbar = toolbar;
        this.hover   = hover;
        this.textSizeSwitcher = textSizeSwitcher;

        this.appendPanel();

    },

    appendPanel : function () {

        if (this.settings.blockId){

            this.getElementById(this.settings.blockId).appendChild(this.toolbar);

            this.toolbar.classList.add('codex-special--included');

            return;

        }

        this.body.appendChild(this.toolbar);

    },

    /**
    *
    */
    addListeners : function () {

        this.colorSwitchers.map(function(switcher, index) {

            switcher.addEventListener('click', codexSpecial.changeColor, false);

        });

        this.textSizeSwitcher.addEventListener('click', codexSpecial.changeTextSize, false);

    },

    draw : {

        element : function (newElement, newClass) {

            var block = document.createElement(newElement);

            block.classList.add(newClass);

            return block;

        },

        toolbar : function () {

            var toolbar = codexSpecial.draw.element('DIV', 'codex-special--toolbar');

            toolbar.innerHTML = '<i class="icon-eye"></i> Контрастная версия';

            return toolbar;

        },

        hover : function () {

            return codexSpecial.draw.element('DIV', 'codex-special--hover');

        },

        /**
        * Makes color switcher
        * @param {String} color
        */
        colorSwitcher : function ( type ) {

            var colorSwitcher = codexSpecial.draw.element('SPAN', 'codex-special--circle');

            colorSwitcher.classList.add('codex-special--circle__' + type);

            return colorSwitcher;

        },

        /**
        * Makes text size toggler
        */
        textSizeSwitcher : function () {

            var textToggler = codexSpecial.draw.element('SPAN', 'codex-special--text');

            // text.name = 'codex-special--text';

            textToggler.innerHTML = '<i class="icon-font"></i> Увеличенный шрифт'

            return textToggler;

        },

    },

    changeColor : function () {

        codexSpecial.dropColor();

        if (this.style.opacity == 1 && !codexSpecial.clean['color']) {

            codexSpecial.clear('color');

            return;

        }

        codexSpecial.hover.style.display = 'block';

        codexSpecial.colorSwitchers.map(function(switcher, index) {

    		switcher.style.opacity = 0.5;

        });

    	this.style.opacity = 1;

        codexSpecial.clean['color'] = false;

    	codexSpecial.body.classList.add(codexSpecial.colorSwitchersClasses[this.dataset.style]);

    },

    dropColor : function () {

    	for (key in codexSpecial.colorSwitchersClasses){

    		codexSpecial.body.classList.remove(codexSpecial.colorSwitchersClasses[key]);

        }

    },

    changeTextSize : function () {

        if (!codexSpecial.clean['textSize']) {

            codexSpecial.clear('textSize');

            return;

        }

        codexSpecial.hover.style.display = 'block';

        codexSpecial.textSizeSwitcher.classList.add('enabled');

        codexSpecial.clean['textSize'] = false;

        codexSpecial.body.classList.add(codexSpecial.textSizeClass);

    },

    dropTextSize : function () {

        codexSpecial.body.classList.remove(codexSpecial.textSizeClass);

        codexSpecial.textSizeSwitcher.classList.remove('enabled');

    },

    clear : function (param) {

        if (param == 'color') {

        	codexSpecial.dropColor();

            codexSpecial.colorSwitchers.map(function(switcher, index) {

                switcher.style.opacity = 1;

            });

            codexSpecial.clean['color'] = true;
        }

        if (param == 'textSize') {

            codexSpecial.dropTextSize();

            codexSpecial.clean['textSize'] = true;
        }

        if (codexSpecial.clean['color'] && codexSpecial.clean['textSize']){

        	codexSpecial.hover.style.display = null;

        }

    },

};

codexSpecial.init({
    //blockId : 'test'
});