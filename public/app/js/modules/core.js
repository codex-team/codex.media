/**
* Significant core methods
*/

module.exports = {

    /** Logging method */
    log : function (str, prefix, type, arg) {

        var staticLength = 32;

        if (prefix) {

            prefix = prefix.length < staticLength ? prefix : prefix.substr( 0, staticLength - 2 );

            while (prefix.length < staticLength - 1) {

                prefix += ' ';

            }

            prefix += ':';
            str = prefix + str;

        }

        type = type || 'log';

        try {

            if ('console' in window && window.console[ type ]) {

                if (arg) console[type](str, arg);
                else console[type](str);

            }

        } catch(e) {}

    },

    /**
    * @return {object} dom element real offset
    */
    getOffset : function (elem) {

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
    isElementOnScreen : function (el) {

        var elPositon    = codex.core.getOffset(el).top,
            screenBottom = window.scrollY + window.innerHeight;

        return screenBottom > elPositon;

    },

    /**
    * Returns computed css styles for element
    * @param {Element} el
    */
    css : function (el) {

        return window.getComputedStyle(el);

    },

    /**
    * Helper for inserting one element after another
    */
    insertAfter : function (target, element) {

        target.parentNode.insertBefore(element, target.nextSibling);

    },

    /**
    * Replaces node with
    * @param {Element} nodeToReplace
    * @param {Element} replaceWith
    */
    replace : function (nodeToReplace, replaceWith) {

        return nodeToReplace.parentNode.replaceChild(replaceWith, nodeToReplace);

    },

    /**
    * Helper for insert one element before another
    */
    insertBefore : function (target, element) {

        target.parentNode.insertBefore(element, target);

    },

    /**
    * Returns random {int} between numbers
    */
    random : function (min, max) {

        return Math.floor(Math.random() * (max - min + 1)) + min;

    },

    /**
    * Attach event to Element in parent
    * @param {Element} parentNode    - Element that holds event
    * @param {string} targetSelector - selector to filter target
    * @param {string} eventName      - name of event
    * @param {function} callback     - callback function
    */
    delegateEvent : function (parentNode, targetSelector, eventName, callback) {

        parentNode.addEventListener(eventName, function (event) {

            var el = event.target, matched;

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
    nodeTypes : {
        TAG     : 1,
        TEXT    : 3,
        COMMENT : 8,
        DOCUMENT_FRAGMENT : 11
    },

    /**
    * Readable keys map
    */
    keys : { BACKSPACE: 8, TAB: 9, ENTER: 13, SHIFT: 16, CTRL: 17, ALT: 18, ESC: 27, SPACE: 32, LEFT: 37, UP: 38, DOWN: 40, RIGHT: 39, DELETE: 46, META: 91 },

    /**
    * @protected
    * Check object for DOM node
    */
    isDomNode : function (el) {

        return el && typeof el === 'object' && el.nodeType && el.nodeType == this.nodeTypes.TAG;

    },

    /**
    * Parses string to nodeList
    * Removes empty text nodes
    * @param {string} inputString
    * @return {array} of nodes
    *
    * Does not supports <tr> and <td> on firts level of inputString
    */

    parseHTML : function (inputString) {

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
    isEmpty : function (obj) {

        return Object.keys(obj).length === 0;

    },

    /**
    * Check for Element visibility
    * @param {Element} el
    */
    isVisible : function (el) {

        return el.offsetParent !== null;

    },

    setCookie : function (name, value, expires, path, domain) {

        var str = name + '=' + value;

        if (expires) str += '; expires=' + expires.toGMTString();
        if (path)    str += '; path=' + path;
        if (domain)  str += '; domain=' + domain;

        document.cookie = str;

    },

    getCookie : function (name) {

        var dc = document.cookie;

        var prefix = name + '=',
            begin = dc.indexOf('; ' + prefix);

        if (begin == -1) {

            begin = dc.indexOf(prefix);
            if (begin !== 0) return null;

        } else
            begin += 2;

        var end = document.cookie.indexOf(';', begin);

        if (end == -1) end = dc.length;

        return unescape(dc.substring(begin + prefix.length, end));

    },

};
