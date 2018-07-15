/**
 * Page types selector in editor form
 */
module.exports = (function () {

    /**
     * @type {HTMLCollectionOf<Element>} items - possible page type items: personal blog, event, community, page, news
     * @type {HTMLElement} pageTypeInput - field with item type value
     * @type {number} pageTypeValue - page type value from database
     */
    let items,
        pageTypeInput,
        pageTypeValue;

    /**
     * Elements classes dictionary
     */
    const CLASSES = {
        pageTypeItem: 'js-form-type-selector__item',
        pageTypeItemSelected: 'form-type-selector__item--selected',
        pageTypeInput: 'js-page-type-input'
    };

    /**
     * Initialize module
     */
    function init(settings) {

        items = document.getElementsByClassName(CLASSES.pageTypeItem);
        pageTypeInput = document.getElementsByClassName(CLASSES.pageTypeInput)[0];
        pageTypeValue = settings.currentType;

        console.log(pageTypeInput);

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
     * Add 'selected' class to item with input value = 1
     * @param {HTMLElement} item - page type item
     */
    function selectChecked(item) {

        if (item.querySelector('input').value === pageTypeValue) {

            item.classList.add(CLASSES.pageTypeItemSelected);
            pageTypeInput.value = item.getElementsByTagName('input')[0].value;

        }

    }

    /**
     * Select item, change input value
     * @param {HTMLElement} item - page type item clicked
     */
    function selectItem() {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove(CLASSES.pageTypeItemSelected);

        }

        this.classList.add(CLASSES.pageTypeItemSelected);

        pageTypeInput.value = this.getElementsByTagName('input')[0].value;

    }

    return {
        init : init
    };


}());