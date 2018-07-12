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

            findSelected(items[i]);

        }

    }

    /**
     * Add 'selected' class to item with input value = 1
     * @param {HTMLElement} item - page type item
     */
    function findSelected(item) {

        if (item.querySelector('input').value == 1) {

            item.classList.add(CLASSES.pageTypeItemSelected);

        }

    }

    /**
     * Select item, change input value
     * @param {HTMLElement} item - page type item clicked
     */
    function selectItem(item) {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove(CLASSES.pageTypeItemSelected);
            items[i].getElementsByTagName('input')[0].value = 0;

        }

        item.classList.add(CLASSES.pageTypeItemSelected);

        item.getElementsByTagName('input')[0].value = 1;

    }

    return {
        init : init,
        selectItem : selectItem
    };


}());