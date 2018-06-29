/**
 * Upload photo from the Community (or User) card
 */
module.exports = function (module) {

    /**
     * Transport type constant, from the Model_File
     * @type {Number}
     */
    const TRANSPORT_PAGE_COVER = 5;

    /**
     * Clickable wrapper
     * @type {Element|null}
     */
    let wrapper = null;

    /**
     * Loading class
     * @type {string}
     */
    let loadingClass = 'loader';

    /**
     * @param {object} response
     * @param {number} response.success
     * @param {object} response.data
     * @param {string} response.data.extension - "jpeg"
     * @param {string} response.data.name - "19c9f40bfa48821cf5e508d55d293cde"
     * @param {string} response.data.size - "95939"
     * @param {string} response.data.target - "577"
     * @param {string} response.data.title - "LtREmqLdNs4"
     * @param {string} response.data.url - "upload/pages/covers/o_19c9f40bfa48821cf5e508d55d293cde.jpg"
     */
    function uploaded(response) {

        try {

            response = JSON.parse(response);

            if (!response.success) {

                error('Unsuccessful uploading');

            }

            let image = wrapper.querySelector('img');

            image.src = '/' + response.data.url.replace('o_', 'b_');

        } catch (err) {

            error(err);

        }

        wrapper.classList.remove(loadingClass);

    }

    function beforeSend() {

        wrapper.classList.add(loadingClass);

    }

    function error(err) {

        codex.core.log('Cover uploading error: %o', '[avatarUploader]', 'warn', err);

    }

    function uploaderClicked(event, pageId) {

        /**
         * Block <a> segue
         */
        event.preventDefault();

        /**
         * Select File and upload
         */
        codex.transport.init({
            url : '/upload/' + TRANSPORT_PAGE_COVER,
            data : {
                target : pageId
            },
            success : uploaded,
            beforeSend : beforeSend,
            error   : error
        });

    }


    /**
     * @param pageId - Community Id
     * @param el - logo holder
     */
    module.init = function ({pageId}, el) {

        wrapper = el;

        el.addEventListener('click', (event) => {

            uploaderClicked(event, pageId);

        });

    };


    return module;

}({});