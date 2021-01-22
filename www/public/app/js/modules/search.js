const ajax = require('@codexteam/ajax');

/**
 * Allow text search starting from the following input length
 */
const MIN_SEARCH_LENGTH = 3;
/**
 * Search debounce timeout — prevents from sending search requests during user input
 */
const SEARCH_TIMEOUT = 500;

const OVERFLOW = {
    HIDDEN: 'hidden',
    AUTO: 'auto'
};

const CSS = {
    loader: 'loader'
};

/**
 * This allows the user to perform a text search on the site's articles.
 */
export default class Search {

    constructor() {

        /**
         * DOM elements involved in search process
         */
        this.elements = {
            holder: null,
            modal: null,
            placeholder: null,
            input: null,
            searchResults: null,
            closer: null,
            loader: null,
        };

    }

    /**
     * Prepare DOM elements to work with
     * @param elementId     - search wrapper id
     * @param modalId       - modal id
     * @param closerId      - modal close button id
     * @param inputId       - search input id
     * @param resultsId     - search results element id
     * @param placeholderId - search placeholder id
     */
    init({elementId, modalId, closerId, inputId, resultsId, placeholderId}) {

        this.elements.holder = document.getElementById(elementId);
        this.elements.modal = document.getElementById(modalId);
        this.elements.closer = document.getElementById(closerId);
        this.elements.input = document.getElementById(inputId);
        this.elements.searchResults = document.getElementById(resultsId);
        this.elements.placeholder = document.getElementById(placeholderId);
        this.elements.loader = this.makeElement('div', CSS.loader);

    }

    toggleOverflow() {

        const currentValue = document.body.style.overflow;

        document.body.style.overflow = (currentValue === OVERFLOW.HIDDEN) ? OVERFLOW.AUTO : OVERFLOW.HIDDEN;

    }

    addListeners() {

        const delayedSearch = codex.core.debounce(
            (value) => this.search(value), SEARCH_TIMEOUT, false
        );

        this.elements.input && this.elements.input.addEventListener(
            'input', (event) => delayedSearch(event.target.value)
        );

        this.elements.closer && this.elements.closer.addEventListener('click', () => this.hide());

    }

    /**
     * Reveals search modal to user & sets up event listeners
     */
    show() {

        this.elements.holder && this.elements.holder.removeAttribute('hidden');
        this.toggleOverflow();
        this.addListeners();

    };

    makeElement(tag, classes, attributes = {}) {

        const element = document.createElement(tag);

        if (Array.isArray(classes)) {

            element.classList.add(...classes);

        } else {

            element.classList.add(classes);

        }

        for (let key in attributes) {

            element.setAttribute(key, attributes[key]);

        }

        return element;

    }

    /**
     * Hide modal and reset related DOM elements appearance
     */
    hide() {

        this.elements.holder.setAttribute('hidden', true);
        this.toggleOverflow();

        this.elements.modal.class = 'search-modal';
        this.elements.input.value = '';

    };

    /**
     * Perform search on user input
     * @param value - input string to search for
     */
    search(value) {

        /**
         * Don't search if input is too short
         */
        if (value.length < MIN_SEARCH_LENGTH) {

            return;

        }

        /**
         * Adjust related DOM elements appearance
         */
        this.elements.searchResults.appendChild(this.elements.loader);
        this.elements.modal.classList.add('search-modal--search-in-progress');

        ajax.get({
            url: '/search',
            data: {
                query: value
            },
            type: ajax.contentType.FORM
        }).then(response => {

            /**
             * Show search results to user
             */
            if (response.body['html']) {

                this.elements.searchResults.innerHTML = response.body['html'];
                this.elements.modal.class = 'search-modal search-modal--search-with-results';

            }

        });

    };

}
