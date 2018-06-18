/**
 *  Module for custom checkboxes
 *
 *  Adds stylized checkbox. You can use them in forms as default checkboxes.
 *  You can user your own styles, just change classes names in classes dictionary
 *
 *  To init module
 *  1. Add element with `cdx-custom-checkbox` name to page.
 *     You can have any other elements in it, checkbox will be added as first child.
 *
 *     `cdx-custom-checkbox` element can have data attributes:
 *      - data-name -- name of checkbox input, that will be in request array.
 *                    By default uses NAMES.defaultInput ('cdx-custom-checkbox')
 *
 *     - data-checked -- if TRUE checkbox will be checked by default
 *
 *      Example:
 *      <span name='cdx-custom-checkbox' data-name='checkbox1' data-checked='false'>I agree</span>
 *
 *  2. Call `init` method of this module
 *  3. If you want to handle changing of checkbox just register event listener on `cdx-custom-checkbox` element with 'toggle' event type:
 *
 *     checkbox.addEventListener('toggle', handler)
 *
 *  @requires checkboxes.css
 *
 *  @author gohabereg
 *  @version 1.0
 */

module.exports = function () {

    /**
     * Custom event for checkboxes. Dispatches when checkbox clicked
     * @type {CustomEvent}
     */
    var ToggleEvent = new window.CustomEvent('toggle'),

        /**
     * Elements classes dictionary
     */
        CLASSES    = {
            wrapper: 'cdx-checkbox',
            checkbox: 'cdx-checkbox__slider',
            checked: 'cdx-checkbox--checked',
            defaultCheckbox: 'cdx-default-checkbox--hidden'
        },
        /**
     * Elements names dictionary
     */
        NAMES      = {
            checkbox: 'cdx-custom-checkbox',
            defaultInput: 'cdx-custom-checkbox'
        };


    /**
     * Creates checkbox element in wrapper with `cdx-custom-checkbox` name
     *
     * @param wrapper - element with `cdx-custom-checkbox` name
     */
    var prepareCheckbox = function (wrapper) {

        var input      = document.createElement('INPUT'),
            checkbox   = document.createElement('SPAN'),
            firstChild = wrapper.firstChild;

        input.type  = 'checkbox';
        input.name  = wrapper.dataset.name || NAMES.defaultInput;
        input.value = 1;
        input.classList.add(CLASSES.defaultCheckbox);

        checkbox.classList.add(CLASSES.checkbox);
        checkbox.appendChild(input);

        wrapper.classList.add(CLASSES.wrapper);
        wrapper.addEventListener('click', clicked);

        if (wrapper.dataset.checked) {

            input.checked = true;

            wrapper.classList.add(CLASSES.checked);

        }

        if (firstChild) {

            wrapper.insertBefore(checkbox, firstChild);

        } else {

            wrapper.appendChild(checkbox);

        }


    };

    /**
     * Handler for click event on checkbox. Toggle checkbox state and dispatch CheckEvent
     */
    var clicked = function () {

        var wrapper  = this,
            checkbox = wrapper.querySelector('.' + CLASSES.checkbox),
            input    = checkbox.querySelector('input');

        checkbox.parentNode.classList.toggle(CLASSES.checked);
        input.checked = !input.checked;

        /**
         * Add `checked` property to CheckEvent
         */
        ToggleEvent.checked = input.checked;

        checkbox.dispatchEvent(ToggleEvent);

    };

    /**
     * Takes all elements with `cdx-custom-checkbox` name and calls prepareCheckbox function for each one
     */
    var init = function () {

        var checkboxes = document.getElementsByName(NAMES.checkbox);

        Array.prototype.forEach.call(checkboxes, prepareCheckbox);

    };

    return {
        init: init
    };

}();