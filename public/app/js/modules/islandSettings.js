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

        var menuTogglers = document.querySelectorAll(settings.selector),
            startIndex   = activated.length,
            endIndex     = menuTogglers.length + activated.length;

        for (var index = startIndex; index < endIndex; index++) {

            /**
             * Save initial object
             */
            activated.push({
                el : menuTogglers[index],
                settings: settings
            });

            prepareToggler(index, menuTogglers[index - startIndex]);

        }

    };

    /**
     * @public
     * Add event listener to the toggler
     * @param  {Number} index   - toggler initial index
     * @param  {Element} toggler
     */
    var prepareToggler = function (index, toggler) {

        /** Save initial selector to specify menu type */
        toggler.dataset.index = index;
        toggler.addEventListener('mouseover', menuTogglerHovered, false);
        toggler.addEventListener('mouseleave', menuTogglerBlurred, false);

    };

    /**
     * @private
     *
     * Island circled-icon mouseover handler
     *
     * @param {Event} event     mouseover-event
     */
    var menuTogglerHovered = function () {

        var menuToggler = this,
            menuParams;

        /** Prevent mouseover handling multiple times */
        if ( menuToggler.dataset.opened == 'true' ) {

            return;

        }

        menuToggler.dataset.opened = true;

        if (!menuHolder) {

            menuHolder = createMenu();

        }

        /**
         * Get current menu params
         * @type {Object}
         */
        menuParams = getMenuParams(menuToggler.dataset.index);

        console.assert(menuParams.items, 'Menu items missed');

        fill(menuParams.items, menuToggler);
        move(menuToggler);

    };

    /**
     * Toggler blur handler
     */
    var menuTogglerBlurred = function () {

        this.dataset.opened = false;

    };

    /**
     * Return menu parametres by toggler index
     * @param {Number}  index  - index got in init() method
     * @return {Object}
     */
    var getMenuParams = function (index) {

        return activated[index].settings;

    };

    /**
     * Fills menu with items
     * @param  {Array}   items     list of menu items
     * @param  {Element} toggler   islan menu icon with data-attributes
     */

    var fill = function (items, toggler) {

        var i,
            itemData,
            itemElement;

        menuHolder.innerHTML = '';

        for (i = 0; !!(itemData = items[i]); i++) {

            itemElement = createItem(itemData);

            /** Save index in dataset for edit-ability */
            itemElement.dataset.itemIndex = i;

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
        itemEl.addEventListener('click', itemClicked);

        return itemEl;

    };

    var itemClicked = function () {

        var itemEl = this,
            togglerIndex = itemEl.dataset.index,
            itemIndex = itemEl.dataset.itemIndex,
            menuParams,
            handler,
            args;

        menuParams = getMenuParams(togglerIndex);

        handler = menuParams.items[itemIndex].handler;
        args    = menuParams.items[itemIndex].arguments;

        handler.call(itemEl, args || {});

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
    * Appends a menu to the container
    * @param {Element} container - where to append menu
    */
    var move = function (container) {

        container.appendChild(menuHolder);
        menuHolder.classList.add(CSS.showed);

    };

    /**
     * @public
     * @description Updates menu item
     * @param  {Number} togglerIndex   - Menu toggler initial index stored in toggler's dataset.index
     * @param  {Number} itemIndex      - Item index stored in item's dataset.itemIndex
     * @param  {String} title          - new title
     * @param  {Function} handler      - new handler
     * @param  {Object} args           - handler arguments
     */
    var updateItem = function (togglerIndex, itemIndex, title, handler, args) {

        console.assert(activated[togglerIndex], 'Toggler was not found by index');

        var currentMenu = activated[togglerIndex],
            currentItemEl = menuHolder.childNodes[itemIndex],
            currentItem;

        if (!currentMenu) {

            return;

        }

        currentItem = activated[togglerIndex].settings.items[itemIndex];

        if ( title ) {

            currentItem.title = title;

        }

        if ( args  ) {

            currentItem.arguments = args;

        }

        if (handler && typeof handler == 'function') {

            currentItem.handler = handler;

        }

        /** Update opened menu item text  */
        if (menuHolder) {

            if ( title ) {

                currentItemEl.textContent = title;

            }

        }

        codex.core.log('item updated %o', 'islandSettings', 'info', currentItem);

    };

    return {
        init : init,
        updateItem : updateItem,
        prepareToggler : prepareToggler
    };

})();
