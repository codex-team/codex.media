/**
* Module for scroll-up button
* @author @scytem @guryn
*/


module.exports = (function () {


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
    var init = function ( layoutHolderId ) {

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
    var scrollPage = function (yCoords) {

        window.scrollTo(0, yCoords);

    };

    /**
    * Hiding Scroll-up button if user on the top of page
    */
    var windowScrollHandler = function () {

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
    var resize = function () {

        var windowWidth     = document.body.clientWidth,
            leftColumtWidth = (windowWidth - layoutWidth) / 2;

        clickableZone.style.width = leftColumtWidth + 'px';

    };


    /**
    * Delay for resize
    */
    var sizeChanged = function () {

        if ( resizeStopWatcher ) {

            window.clearTimeout(resizeStopWatcher);

        }

        resizeStopWatcher = window.setTimeout(resize, 150);

    };

    /**
     * Makes scroll-up arrow and wrapper
     */
    var makeUI = function () {

        var wrapper = document.createElement('DIV'),
            arrow   = document.createElement('DIV');

        wrapper.classList.add('scroll-up');
        arrow.classList.add('scroll-up__arrow');

        wrapper.appendChild(arrow);
        document.body.appendChild(wrapper);

        return wrapper;

    };

    return {
        init: init
    };

}());
