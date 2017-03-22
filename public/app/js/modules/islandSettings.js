/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = (function () {

    /**
     * Menu block cache
     * @type {Element|null}
     */
    var menuHolder = null;

    /**
     * Activated menus
     * @type {Array}
     */
    var activated = [];


    /**
     * Is menu opened
     * @type {Boolean}
     */
    var opened = false;

    /**
     * CSS class names
     * @type {Object}
     */
    var CSS = {
        menu   : 'island-settings__menu',
        item   : 'island-settings__item',
        showed : 'island-settings__menu--showed'

    };

    /**
     * Initialization
     * @param  {Object} settings  - initial settings
     */
    var init = function (settings) {

        var menuTogglers = document.querySelectorAll(settings.selector);

        if (!menuTogglers.length) {

            codex.core.log('Elements %o was not found', 'islandSettings', 'log', settings.selector);
            return;

        }

        /**
         * Save initial object
         */
        activated.push(settings);

        for (var i = menuTogglers.length - 1; i >= 0; i--) {

            /** Save initial selector to specify menu type */
            menuTogglers[i].dataset.selector = settings.selector;

            /** Add event listener */
            menuTogglers[i].addEventListener('click', menuTogglerClicked, false);

        }

    };

    /**
     * Return menu parametres by initial selector
     * @param {string}  initialSelector  - selector passed in init() method
     * @return {Object}
     */
    var getMenuParams = function (initialSelector) {

        return activated.filter(compareSelector, initialSelector).pop();

    };

    /**
     * Find settings object by selector
     *
     * @param  {Object}  obj    passed object with 'selector' key
     * @this   {String}         selector to compare with
     *
     * @return {Boolean}        true if passed selector same as looking for
     */
    var compareSelector = function (obj) {

        return obj.selector == this;

    };

    /**
     * @private
     *
     * Island circled-icon click handler
     *
     * @param {Event} event     click-event
     */
    var menuTogglerClicked = function () {

        var menuToggler = this,
            menuParams = getMenuParams(menuToggler.dataset.selector);

        console.log('menuToggler.dataset.opened: %o', menuToggler.dataset.opened);
        console.log('opened: %o', opened);

        /** Click on the same icon where it was previously opened */
        if ( menuToggler.dataset.opened == "true" ) {

            hide();
            delete menuToggler.dataset.opened;
            return;

        /** opened on other menu item */
        } else if ( opened ){

            console.log('opened on other!');

        }

        if (!menuHolder) {

            menuHolder = createMenu();

        }



        console.assert(menuParams.items, 'Menu items missed');

        fillMenu(menuParams.items, menuToggler);

        show(this);
        menuToggler.dataset.opened = true;

    };

    /**
     * Fills menu with items
     * @param  {Array}   items     list of menu items
     * @param  {Element} toggler   islan menu icon with data-attributes
     */
    var fillMenu = function (items, toggler) {

        var i,
            itemData,
            itemElement;

        menuHolder.innerHTML = '';

        for (i = 0; !!(itemData = items[i]); i++) {

            itemElement = createItem(itemData);

            /** Pass all parametres stored in icon's dataset to the item's dataset */
            for (var attr in toggler.dataset) {

                itemElement.dataset[attr] = toggler.dataset[attr];

            }

            menuHolder.appendChild(itemElement);

        }

    };

    /**
    * @private
    * Creates an option block
    * @param {Object}   item          - menu item data
    * @param {String}   item.title    - title
    * @param {Function} item.handler  - click handler
    *
    * @return {Element} menu item with click handler
    */
    var createItem = function ( item ) {

        var itemEl = document.createElement('LI');

        itemEl.classList.add(CSS.item);

        console.assert(item.title, 'islandSettings: item title is missed');
        console.assert(typeof item.handler == 'function', 'islandSettings: item handler is not a function');

        itemEl.textContent = item.title;
        itemEl.addEventListener('click', item.handler);

        return itemEl;

    };

    /**
    * @private
    * Creates the dropdown menu
    */
    var createMenu = function () {

        var block = document.createElement('UL');

        block.classList.add(CSS.menu);

        return block;

    };

    /**
    * Hides the menu
    */
    var hide = function () {

        if (!menuHolder) {

            return;

        }

        menuHolder.remove();
        menuHolder = null;
        opened = false;

    };

    /**
    * Appends a menu to the container
    * @param {Element} container - where to append menu
    */
    var show = function (container) {

        container.appendChild(menuHolder);

        window.setTimeout(function () {

            menuHolder.classList.add(CSS.showed);
            opened = true;

        }, 50);

    };

    /**
     * Hides menu if clicked on document
     * @param  {Event}  click event
     */
    var documentClicked = function ( event ) {

        if (!opened || event.target.classList.contains(CSS.item)) {

            return;

        }

        hide();

    };

    document.addEventListener('click', documentClicked, false);

    return {
        init: init
    };

})();
