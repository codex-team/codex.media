/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = function (settings) {

    /**
    * Module settings
    * @var {string} holderClass - menu toggle button class
    * @var {string} menuClass - menu block class
    * @var {string} menuClosedClass - closed menu block class
    * @var {string} menuCSSClass - menu block css class
    * @var {Element} menuBlock - menu block element
    * @var {object} menuOptions - associated list of menu options
    */
    var holderClass = null,
        menuClass = null,
        menuClosedClass = null,
        menuCSSClass = null,
        menuBlock = null,
        menuOptions = null;

    /**
    * @private
    * Creates an option block
    * @param {string} title - textContent of the HTML element
    * @param {function} callback - onclick action
    */
    var _createOptionBlock = function (title, callback) {

        var optionBlock = document.createElement('a');

        optionBlock.textContent = title;
        optionBlock.addEventListener('click', callback);

        return optionBlock;

    };

    /**
    * @private
    * Creates the dropdown menu block
    */
    var _createMenuBlock = function () {

        var block = document.createElement('div');

        block.classList.add(menuClass, menuClosedClass, menuCSSClass);

        for (var option in menuOptions) {

            block.appendChild(_createOptionBlock(option, menuOptions[option]));

        }

        return block;

    };

    /**
    * @private
    * Toggles menu of the event target
    */
    var _toggleMenu = function (event) {

        var target = event.currentTarget;

        console.log('Triggering click event on', target);

        if (!target.classList.contains(holderClass)) {

            menuBlock.classList.add(menuClosedClass);

        } else {

            event.stopPropagation();

            if (!(target == menuBlock.parentNode)) {

                target.appendChild(menuBlock);
                menuBlock.classList.remove(menuClosedClass);

            } else {

                menuBlock.classList.toggle(menuClosedClass);

            }

        }

    };

    /**
    * @private
    * Adds event listener to every element with class holderClass
    */
    var _addClickListeners = function () {

        var menuHolders = document.getElementsByClassName(holderClass);

        for (var i = 0; i < menuHolders.length; i++) {

            menuHolders[i].addEventListener('click', _toggleMenu);

        };

        document.body.addEventListener('click', _toggleMenu);

    };

    /**
    * @public
    * Initializes the module with given settings
    * @param {object} options - module settings
    */
    var init = function (options) {

        holderClass = options.holderClass;
        menuClass = options.menuClass;
        menuClosedClass = options.menuClosedClass;
        menuCSSClass = options.menuCSSClass;
        menuOptions = options.menuOptions;
        menuBlock = _createMenuBlock();

        document.body.appendChild(menuBlock);

        _addClickListeners();

    };

    init(settings);

};
