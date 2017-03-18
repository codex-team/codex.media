/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = function (settings) {

    var holderClass = null,
        menuClass = null,
        menuBlock = null,
        menuOptions = null;

    /**
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
    * Creates the dropdown menu block
    */
    var _createMenuBlock = function () {

        var block = document.createElement('div');

        block.classList.add(menuClass, 'dropdown-menu');
        block.style.display = 'none';

        for (var option in menuOptions) {

            block.appendChild(_createOptionBlock(option, menuOptions[option]));

        }

        return block;

    };

    /**
    * Toggles menu of the event target
    */
    var _toggleMenu = function (event) {

        var target = event.currentTarget;

        var flag = false;

        if (menuBlock.style.display != 'none') {

            if (menuBlock.parentNode == target) {

                flag = true;

            }

            menuBlock.style.display = 'none';

        }

        if (flag) {

            return;

        }

        menuBlock.style.display = '';

        target.appendChild(menuBlock);

    };

    /**
    * Adds event listener to every element with class holderClass
    */
    var _addClickListeners = function () {

        var menuHolders = document.getElementsByClassName(holderClass);

        for (var i = 0; i < menuHolders.length; i++) {

            menuHolders[i].addEventListener('click', _toggleMenu);

        };

    };

    var init = function (options) {

        holderClass = options.holderClass;
        menuClass = options.menuClass;
        menuOptions = options.menuOptions;
        menuBlock = _createMenuBlock();

        document.body.appendChild(menuBlock);

        _addClickListeners();

    };

    init(settings);

};
