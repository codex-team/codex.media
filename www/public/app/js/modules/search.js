const ajax = require('@codexteam/ajax');

const MIN_SEARCH_LENGTH = 3;
const SEARCH_TIMEOUT = 1000;

const search = {

    elements: {
        modal: null,
        placeholder: null,
        input: null,
        searchResults: null,
        closer: null,
    },

    wait: false,

    init: function ({elementId, closerId, inputId, resultsId, placeholderId}) {

        this.elements.modal = document.getElementById(elementId);
        this.elements.closer = document.getElementById(closerId);
        this.elements.input = document.getElementById(inputId);
        this.elements.searchResults = document.getElementById(resultsId);
        this.elements.placeholder = document.getElementById(placeholderId);

    },

    show: function () {

        if (this.elements.modal) {

            this.elements.modal.removeAttribute('hidden');
            document.body.style.overflow = 'hidden';

        }

        this.elements.closer && this.elements.closer.addEventListener('click', () => this.hide());

        this.elements.input && this.elements.input.addEventListener(
            'keydown', (event) => this.search(event.target.value)
        );

    },

    hide: function () {

        this.elements.modal.setAttribute('hidden', true);
        document.body.style.overflow = 'auto';

        this.elements.searchResults.setAttribute('hidden', true);
        this.elements.placeholder.removeAttribute('hidden');
        this.elements.input.value = '';

    },

    search: function (value) {

        if (value.length < MIN_SEARCH_LENGTH || this.wait) {

            return;

        }

        ajax.get({
            url: '/search',
            data: {
                query: value
            },
            type: ajax.contentType.FORM
        }).then(response => {

            if (response.body['html']) {

                this.elements.searchResults.removeAttribute('hidden');
                this.elements.searchResults.innerHTML = response.body['html'];

                this.elements.placeholder.setAttribute('hidden', true);

            }

        });

        this.wait = true;

        setTimeout(() => this.wait = false, SEARCH_TIMEOUT);

    }
};

module.exports = search;
