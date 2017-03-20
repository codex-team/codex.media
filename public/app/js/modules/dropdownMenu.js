/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = (function () {

    /**
    * Module settings
    * @var {string} holderClass - menu toggle button class
    * @var {string} menuId - menu id
    * @var {string} menuStyleClass - menu css class
    * @var {object} optionsDict - menu options' callbacks object
    */
    var holderClass = null,
        menuId = null,
        menuStyleClass = null,
        optionsDict = {
            'article': {
                'Редактировать': function () {

                    window.alert('This is a dummy text. Actual callback function is not implemented');

                },
                'Удалить': function () {

                    window.alert('This is a dummy text. Actual callback function is not implemented');

                },
                'Поделиться': function () {

                    window.alert('This is a dummy text. Actual callback function is not implemented');

                }
            },
            'comment': {
                'Редактировать': function () {

                    window.alert('This is a dummy text. Actual callback function is not implemented');

                },
                'Удалить': function () {

                    window.alert('This is a dummy text. Actual callback function is not implemented');

                }
            }
        };

    /**
    * @private
    * Creates an option block
    * @param {string} title - textContent of the HTML element
    * @param {function} callback - onclick action
    */
    var _createOptionBlock = function (title, callback) {

        var optionBlock = document.createElement('div');

        optionBlock.textContent = title;
        optionBlock.addEventListener('click', callback);

        return optionBlock;

    };

    /**
    * @private
    * Creates the dropdown menu block
    */
    var _createMenuBlock = function (entity) {

        var block = document.createElement('div');

        block.id = menuId;
        block.classList.add(menuStyleClass);

        for (var option in optionsDict[entity]) {

            block.appendChild(_createOptionBlock(option, optionsDict[entity][option]));

        }

        return block;

    };

    /**
    * @private
    * Finds a menu or returns null
    */
    var _getMenu = function () {

        return document.getElementById(menuId);

    };

    /**
    * @public
    * Hides the menu
    * @param {Element} menu - menu DOM Element
    */
    var _hide = function (menu) {

        if (menu) {

            menu.parentNode.removeChild(menu);

        }

    };

    /**
    * @public
    * Appends a menu of given entity to given container
    * @param {string} entity - menu entity type
    * @param {Element} container - menu container
    */
    var _show = function (entity, container) {

        container.appendChild(_createMenuBlock(entity));


    };

    /**
    * @public
    * Toggles the menu state
    * @param {MouseEvent} event - click event
    * @param {string} entity - menu entity type
    */
    var toggle = function (event, entity) {

        event.stopPropagation();

        if (holderClass === null) {

            init();

        };

        var target = event.currentTarget;
        var menu = _getMenu();

        if (menu) {

            if (menu.parentNode == target) {

                _hide(menu);

            } else {

                _hide(menu);
                _show(entity, target);

            }

        } else {

            _show(entity, target);

        }

    };

    var init = function () {

        menuId = 'js-dropdown-menu';
        menuStyleClass = 'dropdown-menu';

        document.body.addEventListener('click', function () {

            _hide(_getMenu());

        });

    };

    return {
        init: init,
        toggle: toggle
    };

})();
