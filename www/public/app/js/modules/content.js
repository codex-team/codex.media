/**
* Operations with pages
*/
module.exports = (function () {

     /**
    * Toggles classname on passed blocks
    * @param {string} selector
    * @param {string} toggled classname
    */
    var toggle = function ( which, marker ) {

        var elements = document.querySelectorAll( which );

        for (var i = elements.length - 1; i >= 0; i--) {

            elements[i].classList.toggle( marker );

        }

    };


    /**
    * Toggles mobile menu
    * Handles clicks on the hamburger icon in header
    */
    var toggleMobileMenu = function ( event ) {

        var menu = document.getElementById('js-mobile-menu-holder'),
            openedClass = 'mobile-menu-holder--opened';

        menu.classList.toggle(openedClass);

        event.stopPropagation();
        event.stopImmediatePropagation();
        event.preventDefault();

    };

    /**
     * Create listener for mobile menu toggler
     * @param {String} id — toggler's id
     */
    var setMobileMenuToggler = function ( id ) {

        var menuToggler = document.getElementById(id);

        if ( !menuToggler ) {

            return;

        }

        menuToggler.addEventListener('click', toggleMobileMenu);

    };


    /**
    * Module uses for toggle custom checkboxes
    * that has 'js-custom-checkbox' class and input[type="checkbox"] included
    * Example:
    * <span class="js-custom-checkbox">
    *    <input type="checkbox" name="" value="1"/>
    * </span>
    */
    var customCheckboxes = {

        /**
        * This class specifies checked custom-checkbox
        * You may set it on serverisde
        */
        CHECKED_CLASS : 'checked',

        init : function () {

            var checkboxes = document.getElementsByClassName('js-custom-checkbox');

            if (checkboxes.length) for (var i = checkboxes.length - 1; i >= 0; i--) {

                checkboxes[i].addEventListener('click', codex.content.customCheckboxes.clicked, false);

            }

        },

        clicked : function () {

            var checkbox  = this,
                input     = this.querySelector('input'),
                isChecked = this.classList.contains(codex.content.customCheckboxes.CHECKED_CLASS);

            checkbox.classList.toggle(codex.content.customCheckboxes.CHECKED_CLASS);

            if (isChecked) {

                input.removeAttribute('checked');

            } else {

                input.setAttribute('checked', 'checked');

            }

        }
    };

    var approvalButtons = {

        CLICKED_CLASS : 'click-again-to-approve',

        init : function () {

            var buttons = document.getElementsByClassName('js-approval-button');

            if (buttons.length) for (var i = buttons.length - 1; i >= 0; i--) {

                buttons[i].addEventListener('click', codex.content.approvalButtons.clicked, false);

            }

        },

        clicked : function (event) {

            var button    = this,
                isClicked = this.classList.contains(codex.content.approvalButtons.CLICKED_CLASS);

            if (!isClicked) {

                /* временное решение, пока нет всплывающего окна подверждения важных действий */
                button.classList.add(codex.content.approvalButtons.CLICKED_CLASS);

                event.preventDefault();

            }

        }
    };

    return {

        toggleMobileMenu : toggleMobileMenu,
        setMobileMenuToggler : setMobileMenuToggler,
        customCheckboxes : customCheckboxes,
        approvalButtons : approvalButtons,
        toggle : toggle

    };

}());
