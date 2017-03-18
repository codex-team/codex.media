/**
* Module for scroll-up button
* @author @scytem @guryn
*/


module.exports = {

    SCROLL_UP_OFFSET : 100,

    CENTER_COLUMN_WIDTH : 1010,

    WRAPPER_WIDTH : 100,

    button : null,

    screenWidth : document.body.clientWidth,



    /** Scroll the document to the begin position
    * @param {number} yCoords Y-coordinate
    */
    scrollPage : function (yCoords) {

        window.scrollTo(0, yCoords);

    },

    /**
    * Hiding Scroll-up button if user on the top of page
    */
    windowScrollHandler : function () {

        var notTheTop = window.pageYOffset > codex.scrollUp.SCROLL_UP_OFFSET;

        if (notTheTop) {

            codex.scrollUp.button.classList.add('show');

        } else {

            codex.scrollUp.button.classList.remove('show');

        }

    },

    /**
    * Resize hover/click area touching user width of screen
    */
    resize : function () {

        var clientWidth = document.body.clientWidth,
            wrapperWitdh = (clientWidth - codex.scrollUp.CENTER_COLUMN_WIDTH) / 2;

        codex.scrollUp.button.style.width = wrapperWitdh + 'px';

        if (wrapperWitdh < codex.scrollUp.WRAPPER_WIDTH) {

            codex.scrollUp.button.style.width = codex.scrollUp.button.style['min-width'];

        }

    },

    /**
    * Init method
    * Fired after document is ready
    */
    init : function () {

        /** Create scroll-up button */
        var arrow = document.createElement('DIV');

        arrow.classList.add('scroll-up__arrow');

        /** Create wrapper for scrollUp arrow */
        this.button = document.createElement('DIV');
        this.button.classList.add('scroll-up');

        this.button.appendChild(arrow);
        document.body.appendChild(this.button);

        /** Bind click event on scroll-up button */
        this.button.addEventListener('click', codex.scrollUp.scrollPage);

        /** Global window scroll handler */
        window.addEventListener('scroll', codex.scrollUp.windowScrollHandler);

        /** Autoresize */
        window.addEventListener('resize', codex.scrollUp.resize, false);

        /** Set size */
        this.resize();

        /* Check heigth */
        this.windowScrollHandler();

    }

};
