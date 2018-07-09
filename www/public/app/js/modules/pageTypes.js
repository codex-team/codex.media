/**
 * Page types selector in editor form
 */
module.exports = (function () {

    /**
     * Get page type items and the wrapper
     * @type {HTMLCollectionOf<Element>}
     */
    let items = document.getElementsByClassName('js-form-type-selector__item'),
        wrapper = document.getElementsByClassName('form-type-selector');

    /**
     * Initialize module
     * Add `selected` class to item with input value = 1
     */
    function init() {

        for (let i = 0; i < items.length; i++) {

            /**
             * If we found element with input value = 1, stop searching
             */
            if (items[i].querySelector('input').value == 1) {

                items[i].classList.add('form-type-selector__item--selected');
                return;

            }

        }

    }

    /**
     * Toggle into view single row of items or show all of them
     */
    function toggleHeight() {

        wrapper[0].classList.toggle('form-type-selector--opened');

    }

    /**
     * Put selected item after `personal blog`, minimize menu height
     * @param {HTMLElement} item - page type item clicked
     */
    function selectItem(item) {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove('form-type-selector__item--selected');
            items[i].getElementsByTagName('input')[0].value = 0;

        }

        item.classList.add('form-type-selector__item--selected');

        item.getElementsByTagName('input')[0].value = 1;

        wrapper[0].classList.remove('form-type-selector--opened');

    }

    return {
        init : init,
        toggleHeight : toggleHeight,
        selectItem : selectItem
    };


}());