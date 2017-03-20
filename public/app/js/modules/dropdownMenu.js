/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = (function () {

    /**
    * Module settings
    * @var {string} holderElement - menu toggle button DOM element
    * @var {string} holderClass - menu toggle button class
    * @var {string} menuClass - menu block class
    * @var {string} menuClosedClass - closed menu block class
    * @var {string} styleClass - menu block css class
    * @var {Element} menuBlock - menu block element
    * @var {object} menuOptions - associated list of menu options
    */
    var holderClass = null,
        holderElement = null,
        menuClass = null,
        menuClosedClass = null,
        styleClass = null,
        menuBlock = null,
        menuOptions = null,
        optionsDict = {
            'Редактировать': function () {

                window.alert('This is a dummy text. Actual callback function is not implemented');

            },
            'Удалить': function () {

                window.alert('This is a dummy text. Actual callback function is not implemented');

            },
            'Поделиться': function () {

                window.alert('This is a dummy text. Actual callback function is not implemented');

            },
        };

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

        block.classList.add(menuClass, menuClosedClass, styleClass);

        for (var option in menuOptions) {

            block.appendChild(_createOptionBlock(option, menuOptions[option]));

        }

        return block;

    };

    /**
    * @private
    * Checks if the menu is opened
    */
    var _menuOpened = function () {

        return !menuBlock.classList.contains(menuClosedClass);

    };

    /**
    * @private
    * Closes the menu
    */
    var _closeMenu = function () {

        menuBlock.classList.add(menuClosedClass);

    };

    /**
    * @private
    * Opens the menu
    */
    var _openMenu = function () {

        menuBlock.classList.remove(menuClosedClass);

    };

    /**
    * @private
    * Toggles menu of the event target
    */
    var _toggleMenu = function (event) {

        var target = event.currentTarget;

        if (!target.classList.contains(holderClass)) {

            _closeMenu();

        } else {

            event.stopPropagation();

            if (!(target == menuBlock.parentNode)) {

                target.appendChild(menuBlock);
                _openMenu();

            } else {

                if (_menuOpened()) {

                    _closeMenu();

                } else {

                    _openMenu();

                }

            }

        }

    };

    /**
    * @private
    * Adds event listener to the element with class holderClass
    */
    var _addClickListeners = function () {

        holderElement.addEventListener('click', _toggleMenu);
        document.body.addEventListener('click', _toggleMenu);

    };

    /**
    * @public
    * Initializes the module with given settings
    * @param {object} options - module settings
    */
    var init = function (options) {

        menuClass = 'js-dropdown-menu';
        menuClosedClass = 'dropdown-menu--closed';

        holderElement = options.holderElement;
        holderClass = options.holderClass;
        styleClass = options.styleClass;
        menuOptions = options.menuOptions || optionsDict;
        menuBlock = _createMenuBlock();

        document.body.appendChild(menuBlock);

        _addClickListeners();

    };

    return {init: init};

})();
