/**
* Module for scroll-up button
*/
module.exports = {

    SCROLL_UP_OFFSET : 100,

    button : null,

    scrollPage : function () {

        window.scrollTo(0, 0);

    },

    windowScrollHandler : function () {

        if (window.pageYOffset > codex.scrollUp.SCROLL_UP_OFFSET) {

            codex.scrollUp.button.classList.add('show');

        } else {

            codex.scrollUp.button.classList.remove('show');

        }

    },

    /**
    * Init method
    * Fired after document is ready
    */
    init : function () {

        /** Create scroll-up button */
        this.button = document.createElement('DIV');
        this.button = document.createElement('DIV');
        this.button.classList.add('scroll-up');

        document.body.appendChild(this.button);

        /** Bind click event on scroll-up button */
        this.button.addEventListener('click', codex.scrollUp.scrollPage);

        /** Global window scroll handler */
        window.addEventListener('scroll', codex.scrollUp.windowScrollHandler);

    }

};
