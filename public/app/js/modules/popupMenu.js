/**
* Pop-up menu module
* Author: @ndawn
*/

module.exports = (function () {

    /**
    * Extends menu of the element
    */
    var _toggleMenu = function (event) {

        var menuBlock = document.getElementsByClassName('js-popup-menu--popup');

        if (menuBlock.length) {

            for (var i = 0; i < menuBlock.length; i++) {

                menuBlock[i].parentNode.removeChild(menuBlock[i]);

            }

            return;

        }

        menuBlock = document.createElement('div');

        menuBlock.className = 'js-popup-menu--popup popup-menu';

        if (event.currentTarget.dataset.menuOptions) {

            var menuOptions = JSON.parse(event.currentTarget.dataset.menuOptions);

        } else {

            return;

        }

        var menuOptionsSize = (function () {

            var size = 0;

            for (var key in menuOptions) {

                if (menuOptions.hasOwnProperty(key)) {

                    size++;

                }

            }

            return size;

        })();

        if (menuOptionsSize) {

            for (var option in menuOptions) {

                var optionBlock = document.createElement('a');

                optionBlock.textContent = option;
                optionBlock.addEventListener('click', function () {

                    eval(menuOptions[option]);

                });

                menuBlock.appendChild(optionBlock);

            }

            event.currentTarget.appendChild(menuBlock);

        }

    };

    /**
    * Adds event listener to every element with class js-popup-menu--holder
    */
    var _addClickListeners = function () {

        var menuables = document.getElementsByClassName('js-popup-menu--holder');

        if (menuables.length) {

            for (var i = 0; i < menuables.length; i++) {

                menuables[i].onclick = _toggleMenu;

            };

        };

    };

    var init = function () {

        _addClickListeners();

    };

    return {init: init};

})();
