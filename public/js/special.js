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
        big   : 'special-big',
    },

    init : function () {

    	this.body = document.getElementsByTagName("body")[0];
    	this.hover = document.getElementsByClassName("codex-special--hover")[0];
    	this.toolbar = document.getElementsByClassName("codex-special--toolbar")[0];

    	this.colorSwitchers = document.getElementsByName("codex-special-color");
        this.textSizeSwitchers = document.getElementsByName("codex-special-text");

    	for (var i = 0, switcher; !!(switcher = codexSpecial.colorSwitchers[i]); i++) {
    		switcher.addEventListener('click', codexSpecial.changeColor, false);
    	}

        for (var i = 0, switcher; !!(switcher = codexSpecial.textSizeSwitchers[i]); i++) {
            switcher.addEventListener('click', codexSpecial.changeTextSize, false);
        }
    },

    changeColor : function () {

        codexSpecial.dropColor();

        console.log(this);

        if (this.style.opacity == 1 && codexSpecial.hover.style.display == 'block') {

            codexSpecial.clear();

            return;

        }

        codexSpecial.hover.style.display = 'block';

    	for (var i = 0, switcher; !!(switcher = codexSpecial.colorSwitchers[i]); i++) {
    		switcher.style.opacity = 0.5;
    	}

    	this.style.opacity = 1;

    	codexSpecial.body.classList.add(codexSpecial.classesColor[this.dataset.style]);

    },

    dropColor : function () {

    	for (key in codexSpecial.classesColor){
    		codexSpecial.body.classList.remove(codexSpecial.classesColor[key]);
    	}

    },

    // changeTextSize : function () {

    //     codexSpecial.hover.style.display = 'block';

    //     codexSpecial.dropTextSize();

    //     for (var i = 0, switcher; !!(switcher = codexSpecial.textSizeSwitchers[i]); i++) {
    //         switcher.style.opacity = 0.5;
    //     }

    //     this.style.opacity = 1;

    //     codexSpecial.body.classList.add(codexSpecial.classesTextSize[this.dataset.style]);

    // },

    // dropTextSize : function () {

    //     for (key in codexSpecial.classesTextSize){
    //         codexSpecial.body.classList.remove(codexSpecial.classesTextSize[key]);
    //     }

    // },

    clear : function () {

    	for (key in codexSpecial.classes){
    		codexSpecial.body.classList.remove(codexSpecial.classes[key]);
    	}

        for (var i = 0, switcher; !!(switcher = codexSpecial.colorSwitchers[i]); i++) {
            switcher.style.opacity = 1;
        }

    	codexSpecial.hover.style.display = null;

    },

};

codexSpecial.init();
