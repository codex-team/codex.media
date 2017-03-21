/**
* Dropdown menu module
* @author: @ndawn
*/

module.exports = (function () {

    /**
    * Module settings
    * @var {string} menuId - menu id
    * @var {string} menuStyleClass - menu css class
    * @var {object} optionsDict - menu options' callbacks object
    */
    var menuId = 'js-dropdown-menu',
        menuStyleClass = 'dropdown-menu',
        optionsDict = {
            'article' : {
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
            'comment' : {
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
    var createOptionBlock = function (title, callback) {

        var optionBlock = document.createElement('DIV');

        optionBlock.textContent = title;
        optionBlock.addEventListener('click', callback);

        return optionBlock;

    };

    /**
    * @private
    * Creates the dropdown menu block of given entity
    * @param {string} entity - menu entity type
    */
    var createMenuBlock = function (entity) {

        var block = document.createElement('DIV');

        block.id = menuId;
        block.classList.add(menuStyleClass);

        for (var option in optionsDict[entity]) {

            block.appendChild(createOptionBlock(option, optionsDict[entity][option]));

        }

        return block;

    };

    /**
    * @private
    * Finds a menu or returns null
    */
    var getMenu = function () {

        return document.getElementById(menuId);

    };

    /**
    * @public
    * Hides the menu
    * @param {Element} menu - menu DOM Element
    */
    var hide = function (menu) {

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
    var show = function (entity, container) {

        container.appendChild(createMenuBlock(entity));

    };

    /**
    * @public
    * Toggles the menu state
    * @param {MouseEvent} event - click event
    * @param {string} entity - menu entity type
    */
    var toggle = function (event, entity) {

        event.stopPropagation();

        document.body.addEventListener('click', function () {

            hide(getMenu());

        });

        var target = event.currentTarget;
        var menu = getMenu();

        if (menu) {

            if (menu.parentNode == target) {

                hide(menu);

            } else {

                hide(menu);
                show(entity, target);

            }

        } else {

            show(entity, target);

        }

    };

    return { toggle : toggle };

})();
