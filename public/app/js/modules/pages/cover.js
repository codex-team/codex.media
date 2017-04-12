/**
 * Actions with page cover
 */
module.exports = function (cover) {

    var css = {
        setCover : 'posts-list-item__set-cover',
        setCoverShowed : 'posts-list-item__set-cover--showed',
    };

    /**
     * Transport type constant
     * @type {Number}
     */
    var TRANSPORT_PAGE_COVER = 5;

    /**
     * Menu 'set-cover' button handler
     * @this menu Element
     */
    cover.toggleButton = function () {

        var pageId = this.dataset.id,
            pageIsland,
            setCoverButton;

        pageIsland = document.getElementById('js-page-' + pageId);

        if (!pageIsland) {

            return;

        }

        setCoverButton = pageIsland.querySelector('.' + css.setCover);
        setCoverButton.classList.toggle(css.setCoverShowed);

        cover.set();

    };

    /**
     * Select file
     */
    cover.set = function () {

        codex.transport.init({
            url : '/upload/' + TRANSPORT_PAGE_COVER,
            success : uploaded,
            error   : error
        });

    };

    /**
     * Success uploading handler
     * @param  {String} response    server answer
     */
    function uploaded( response ) {

        response = JSON.parse(response);

        if ( !response.success ) {

            codex.alerts.show({
                type: 'error',
                message: response.message || 'File uploading error :('
            });

            return;

        }

        console.assert( response.data && response.data.url, 'Wrong response data');

        update( response.data );

    }

    /**
     * Update
     * @param  {String} imageSource  - new cover src
     */
    function update( imageData ) {

        console.log('imageData: %o', imageData);

        var img = document.createElement('IMG');

        img.onload = function () {

            console.log('imageData.target: %o', imageData.target);

        };

        img.src = imageData.url;

    }

    /**
     * Uploading error
     * @param  {Object} error
     */
    var error = function (uploadError) {

        codex.core.log('Cover uploading error: %o', '[pages.cover]', 'warn', uploadError);

    };

    return cover;

}({});