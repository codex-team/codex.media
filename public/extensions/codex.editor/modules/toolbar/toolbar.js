/**
 * Codex Editor toolbar module
 *
 * Contains:
 *  - Inline toolbox
 *  - Toolbox within plus button
 *  - Settings section
 *
 * @author Codex Team
 * @version 1.0
 */

let editor = codex.editor;

module.exports = (function (toolbar) {

    toolbar.settings = require('./settings');
    toolbar.inline   = require('./inline');
    toolbar.toolbox  = require('./toolbox');

    /**
     * Margin between focused node and toolbar
     */
    toolbar.defaultToolbarHeight = 49;

    toolbar.defaultOffset = 34;

    toolbar.opened = false;

    toolbar.current = null;

    /**
     * @protected
     */
    toolbar.open = function () {

        editor.nodes.toolbar.classList.add('opened');
        this.opened = true;

    };

    /**
     * @protected
     */
    toolbar.close = function () {

        editor.nodes.toolbar.classList.remove('opened');

        toolbar.opened  = false;
        toolbar.current = null;

        for (var button in editor.nodes.toolbarButtons) {

            editor.nodes.toolbarButtons[button].classList.remove('selected');

        }

        /** Close toolbox when toolbar is not displayed */
        editor.toolbar.toolbox.close();
        editor.toolbar.settings.close();

    };

    toolbar.toggle = function () {

        if ( !this.opened ) {

            this.open();

        } else {

            this.close();

        }

    };

    toolbar.hidePlusButton = function () {

        editor.nodes.plusButton.classList.add('hide');

    };

    toolbar.showPlusButton = function () {

        editor.nodes.plusButton.classList.remove('hide');

    };

    /**
     * Moving toolbar to the specified node
     */
    toolbar.move = function () {

        /** Close Toolbox when we move toolbar */
        editor.toolbar.toolbox.close();

        if (!editor.content.currentNode) {

            return;

        }

        var newYCoordinate = editor.content.currentNode.offsetTop - (editor.toolbar.defaultToolbarHeight / 2) + editor.toolbar.defaultOffset;

        editor.nodes.toolbar.style.transform = `translate3D(0, ${Math.floor(newYCoordinate)}px, 0)`;

        /** Close trash actions */
        editor.toolbar.settings.hideRemoveActions();

    };

    return toolbar;

})({});