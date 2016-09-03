var codexSpecial = {

	body    : null,
	toolbar : null,
	hover   : null,

	colorSwitchers : [],

    textSizeSwitchers : [],

	classesColor : {
		green : 'special-green',
		blue  : 'special-blue',
		white : 'special-white',
	},

    classesTextSize : {
        big       : 'special-big-15',
        bigger    : 'special-big-20',
        biggest   : 'special-big-25',
    },

    clean : {
        color    : true,
        textSize : true,
    },

    init : function () {

    	this.body = document.getElementsByTagName("body")[0];
    	this.hover = document.getElementsByClassName("codex-special--hover")[0];
    	this.toolbar = document.getElementsByClassName("codex-special--toolbar")[0];

    	this.colorSwitchers = document.getElementsByName("codex-special--color");
        this.textSizeSwitchers = document.getElementsByName("codex-special--text");

    	for (var i = 0, switcher; !!(switcher = codexSpecial.colorSwitchers[i]); i++) {
    		switcher.addEventListener('click', codexSpecial.changeColor, false);
    	}

        for (var i = 0, switcher; !!(switcher = codexSpecial.textSizeSwitchers[i]); i++) {
            switcher.addEventListener('click', codexSpecial.changeTextSize, false);
        }
    },

    changeColor : function () {

        codexSpecial.dropColor();

        if (this.style.opacity == 1 && !codexSpecial.clean['color']) {

            codexSpecial.clear('color');

            return;

        }

        codexSpecial.hover.style.display = 'block';

    	for (var i = 0, switcher; !!(switcher = codexSpecial.colorSwitchers[i]); i++) {
    		switcher.style.opacity = 0.5;
    	}

    	this.style.opacity = 1;

        codexSpecial.clean['color'] = false;

    	codexSpecial.body.classList.add(codexSpecial.classesColor[this.dataset.style]);

    },

    dropColor : function () {

    	for (key in codexSpecial.classesColor){
    		codexSpecial.body.classList.remove(codexSpecial.classesColor[key]);
    	}

    },

    changeTextSize : function () {

        codexSpecial.dropTextSize();

        if (this.style.opacity == 1 && !codexSpecial.clean['textSize']) {

            codexSpecial.clear('textSize');

            return;

        }

        codexSpecial.hover.style.display = 'block';

        for (var i = 0, switcher; !!(switcher = codexSpecial.textSizeSwitchers[i]); i++) {
            switcher.style.opacity = 0.5;
        }

        this.style.opacity = 1;

        codexSpecial.clean['textSize'] = false;

        codexSpecial.body.classList.add(codexSpecial.classesTextSize[this.dataset.style]);

    },

    dropTextSize : function () {

        for (key in codexSpecial.classesTextSize){
            codexSpecial.body.classList.remove(codexSpecial.classesTextSize[key]);
        }

    },

    clear : function (param) {

        if (param == 'color') {

        	codexSpecial.dropColor();

            for (var i = 0, switcher; !!(switcher = codexSpecial.colorSwitchers[i]); i++) {
                switcher.style.opacity = 1;
            }

            codexSpecial.clean['color'] = true;
        }

        if (param == 'textSize') {

            codexSpecial.dropTextSize();

            for (var i = 0, switcher; !!(switcher = codexSpecial.textSizeSwitchers[i]); i++) {
                switcher.style.opacity = 1;
            }

            codexSpecial.clean['textSize'] = true;
        }

        if (codexSpecial.clean['color'] && codexSpecial.clean['textSize']){

        	codexSpecial.hover.style.display = null;

        }

    },

};

codexSpecial.init();
