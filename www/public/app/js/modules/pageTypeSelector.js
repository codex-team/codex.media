/**
 * Page types selector in editor form
 */
module.exports = (function () {

    /**
     * Possible page type items: personal blog, event, community, page, news
     * @type {HTMLCollectionOf<Element>}
     */
    let items;

    /**
     * Field with item type value
     * @type {HTMLElement}
     */
    let pageTypeInput;

    /**
     * Page type value from database
     * @type {number}
     */
    let pageTypeValue;

    /**
     * Optional form fields according to page type
     * @type {HTMLElement}
     */
    let pageTypeFields;

    /**
     * Elements classes dictionary
     */
    const CLASSES = {
        pageTypeItem: 'js-form-type-selector__item',
        pageTypeItemSelected: 'js-form-type-selector__item--selected',
        pageTypeInput: 'js-page-type-input',
        pageTypeFields: 'js-page-options__item'
    };

    /**
     * Initialize module
     */
    function init(settings) {

        items = document.getElementsByClassName(CLASSES.pageTypeItem);
        pageTypeInput = document.getElementsByClassName(CLASSES.pageTypeInput)[0];
        pageTypeValue = settings.currentType;
        pageTypeFields = document.getElementsByClassName(CLASSES.pageTypeFields);

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
     * Add 'selected' class to item with data-type value same as current page type value
     * @param {HTMLElement} item - page type item
     */
    function selectChecked(item) {

        /**
         * @type {HTMLElement} itemDataValue -  data-type value of page type item
         */
        let itemDataValue = item.dataset.type;

        if (itemDataValue === pageTypeValue) {

            item.classList.add(CLASSES.pageTypeItemSelected);
            pageTypeInput.value = itemDataValue;

        }

        optionalTypeFields(pageTypeValue);

    }

    /**
     * Select item, pass its value to hidden form input
     */
    function selectItem() {

        for (let i = 0; i < items.length; i++) {

            items[i].classList.remove(CLASSES.pageTypeItemSelected);

        }

        this.classList.add(CLASSES.pageTypeItemSelected);

        pageTypeInput.value = this.dataset.type;

        optionalTypeFields(pageTypeInput.value);

    }

    /**
     * Hide optional fields for all types of pages
     */
    function hideOptionalFields() {

        for (let i = 0; i < pageTypeFields.length; i++) {

            pageTypeFields[i].classList.add('hide');

        }

    }

    /**
     * Show optional fields for chosen page type
     * @param {number} pageType
     */
    function optionalTypeFields(pageType) {

        hideOptionalFields();

        for (let i = 0; i < pageTypeFields.length; i++) {

            if (pageTypeFields[i].getAttribute('data-page-type') === pageType) {

                pageTypeFields[i].classList.remove('hide');

            }

        }

    }

    return {
        init : init
    };

}());