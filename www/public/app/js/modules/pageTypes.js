/**
 * Page types selector in editor form
 */
module.exports = (function () {

    /**
     * Get page type items
     * @type {HTMLCollectionOf<Element>}
     */
    let items = document.getElementsByClassName('js-form-type-selector__item');

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
     * Select item, change input value
     * @param {HTMLElement} item - page type item clicked
     */
    function selectItem(item) {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove('form-type-selector__item--selected');
            items[i].getElementsByTagName('input')[0].value = 0;

        }

        item.classList.add('form-type-selector__item--selected');

        item.getElementsByTagName('input')[0].value = 1;

    }

    return {
        init : init,
        selectItem : selectItem
    };


}());