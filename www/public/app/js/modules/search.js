const ajax = require('@codexteam/ajax');

const MIN_SEARCH_LENGTH = 3;
const SEARCH_TIMEOUT = 500;

export default class Search {

    constructor() {

        /**
         * DOM elements involved in search process
         */
        this.elements = {
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
     * @param elementId     - search modal id
     * @param closerId      - modal close button id
     * @param inputId       - search input id
     * @param resultsId     - search results element id
     * @param placeholderId - search placeholder id
     */
    init({elementId, closerId, inputId, resultsId, placeholderId}) {

        this.elements.modal = document.getElementById(elementId);
        this.elements.closer = document.getElementById(closerId);
        this.elements.input = document.getElementById(inputId);
        this.elements.searchResults = document.getElementById(resultsId);
        this.elements.placeholder = document.getElementById(placeholderId);
        this.elements.loader = this.createLoader();

    }

    /**
     * Reveals search modal to user & sets up event listeners
     */
    show() {

        if (this.elements.modal) {

            this.elements.modal.removeAttribute('hidden');
            document.body.style.overflow = 'hidden';

        }

        this.elements.closer && this.elements.closer.addEventListener('click', () => this.hide());

        const delayedSearch = codex.core.debounce(
            (value) => this.search(value), SEARCH_TIMEOUT, false
        );

        this.elements.input && this.elements.input.addEventListener(
            'input', (event) => delayedSearch(event.target.value)
        );

    };

    /**
     * Creates loader to show while search is in progress
     * @returns {HTMLDivElement}
     */
    createLoader() {

        const loader = document.createElement('div');

        loader.classList.add('loader');

        return loader;

    };

    /**
     * Hide modal and reset related DOM elements appearance
     */
    hide() {

        this.elements.modal.setAttribute('hidden', true);
        document.body.style.overflow = 'auto';

        this.elements.searchResults.setAttribute('hidden', true);
        this.elements.placeholder.removeAttribute('hidden');
        this.elements.input.value = '';

    };

    /**
     * Perform search on user input
     * @param value
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
        this.elements.placeholder.setAttribute('hidden', true);
        this.elements.searchResults.removeAttribute('hidden');
        this.elements.searchResults.classList.add('loading');
        this.elements.searchResults.appendChild(this.elements.loader);

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
                this.elements.searchResults.classList.remove('loading');

            }

        });

    };

}
