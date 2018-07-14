/**
 * Page types selector in editor form
 */
module.exports = (function () {

    /**
     * @type {HTMLCollectionOf<Element>} items - possible page type items: personal blog, event, community, page, news
     */
    let items;

    /**
     * Elements classes dictionary
     */
    const CLASSES = {
        pageTypeItem: 'js-form-type-selector__item',
        pageTypeItemSelected: 'form-type-selector__item--selected'
    };

    /**
     * Initialize module
     */
    function init() {

        items = document.getElementsByClassName(CLASSES.pageTypeItem);

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

        if (item.querySelector('input').value) {

            item.classList.add(CLASSES.pageTypeItemSelected);

        }

    }

    /**
     * Select item, change input value
     * @param {HTMLElement} item - page type item clicked
     */
    function selectItem() {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove(CLASSES.pageTypeItemSelected);
            items[i].getElementsByTagName('input')[0].value = 0;

        }

        this.classList.add(CLASSES.pageTypeItemSelected);

        this.getElementsByTagName('input')[0].value = 1;

    }

    return {
        init : init
    };


}());