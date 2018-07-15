/**
 * Page types selector in editor form
 */
module.exports = (function () {

    /**
     * @type {HTMLCollectionOf<Element>} items - possible page type items: personal blog, event, community, page, news
     */
    let items;
    /**
     * @type {HTMLElement} pageTypeInput - field with item type value
     */
    let pageTypeInput;
    /**
     * @type {number} pageTypeValue - page type value from database
     */
    let pageTypeValue;

    /**
     * Elements classes dictionary
     */
    const CLASSES = {
        pageTypeItem: 'js-form-type-selector__item',
        pageTypeItemSelected: 'js-form-type-selector__item--selected',
        pageTypeInput: 'js-page-type-input'
    };

    /**
     * Initialize module
     */
    function init(settings) {

        items = document.getElementsByClassName(CLASSES.pageTypeItem);
        pageTypeInput = document.getElementsByClassName(CLASSES.pageTypeInput)[0];
        pageTypeValue = settings.currentType;

        for (let i = 0; i < items.length; i++) {

            selectChecked(items[i]);
            addListener(items[i]);

        }

    }

    /**
     * Add event listeners to page type items
     * @param {HTMLElement} item - page type item
     */
    function addListener(item) {

        item.addEventListener('click', selectItem);

    }

    /**
     * Add 'selected' class to item with input value same as current page type value
     * @param {HTMLElement} item - page type item
     */
    function selectChecked(item) {

        /**
         * @type {HTMLElement} itemInputValue - input value of page type item
         */
        let itemInputValue = item.querySelector('input').value;

        if (itemInputValue === pageTypeValue) {

            item.classList.add(CLASSES.pageTypeItemSelected);
            pageTypeInput.value = itemInputValue;

        }

    }

    /**
     * Select item, pass its value to hidden form input
     */
    function selectItem() {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove(CLASSES.pageTypeItemSelected);

        }

        this.classList.add(CLASSES.pageTypeItemSelected);

        pageTypeInput.value = this.querySelector('input').value;

    }

    return {
        init : init
    };


}());