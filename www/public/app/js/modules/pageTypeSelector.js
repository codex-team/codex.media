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
     * Page types constants
     */
    const PAGE_TYPE_VALUES = {
        PAGE: '1',
        BLOG: '2',
        NEWS: '3',
        COMMUNITY: '4',
        EVENT: '5'
    };

    /**
     * Page options classes for various page types
     */
    const PAGE_TYPE_CLASSES = {
        NEWS: 'js-page-options__item--news',
        COMMUNITY: 'js-page-options__item--community',
        EVENT: 'js-page-options__item--event'
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

        switch (pageType) {

            case PAGE_TYPE_VALUES.PAGE:
                break;
            case PAGE_TYPE_VALUES.BLOG:
                break;
            case PAGE_TYPE_VALUES.NEWS:
                document.getElementsByClassName(PAGE_TYPE_CLASSES.NEWS)[0].classList.remove('hide');
                break;
            case PAGE_TYPE_VALUES.COMMUNITY:
                document.getElementsByClassName(PAGE_TYPE_CLASSES.COMMUNITY)[0].classList.remove('hide');
                break;
            case PAGE_TYPE_VALUES.EVENT:
                document.getElementsByClassName(PAGE_TYPE_CLASSES.EVENT)[0].classList.remove('hide');
                break;
            default:
                break;

        }

    }

    return {
        init : init
    };

}());