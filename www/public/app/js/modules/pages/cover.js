/**
 * Actions with page cover
 */
module.exports = function (cover) {

    var css = {
        cover          : 'posts-list-item__cover',
        setCover       : 'posts-list-item__cover--empty',
        setCoverShowed : 'posts-list-item__cover--empty-showed',
        preview        : 'posts-list-item__cover--preview',
        withSmallCover : 'posts-list-item--with-small-cover',
        withBigCover   : 'posts-list-item--with-big-cover'
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
    cover.toggleButton = function (coverSizeClass) {

        var pageId = this.dataset.id,
            pageIsland,
            setCoverButton;

        pageIsland = document.getElementById('js-page-' + pageId);

        if (!pageIsland) {

            return;

        }

        setCoverButton = pageIsland.querySelector('.' + css.cover);

        if (coverSizeClass && coverSizeClass == css.withSmallCover) {

            pageIsland.classList.toggle(css.withBigCover, false);
            pageIsland.classList.add(css.withSmallCover);

        } else if (coverSizeClass && coverSizeClass == css.withBigCover) {

            pageIsland.classList.toggle(css.withSmallCover, false);
            pageIsland.classList.add(css.withBigCover);

        }

        setCoverButton.classList.add(css.setCover);
        setCoverButton.classList.toggle(css.setCoverShowed);

        /** Let user see cover-button, than click on it */
        window.setTimeout(function () {

            cover.set(pageId);

        }, 300);

    };

    /**
     * Select file
     * @param {Number} pageId - cover's page id
     */
    cover.set = function ( pageId ) {

        if ( isNaN(pageId) ) {

            codex.core.log('Wrong pageId passed %o', '[page.cover]', 'warn', pageId);
            return;

        }

        codex.transport.init({
            url : '/upload/' + TRANSPORT_PAGE_COVER,
            data : {
                target : pageId
            },
            success : uploaded,
            beforeSend : beforeSend.bind(pageId),
            error   : error
        });

    };

    /**
     * Makes preview
     * @this {pageId}
     */
    function beforeSend() {

        var pageId = this,
            article = document.getElementById('js-page-' + pageId),
            coverHolder;

        if (!article) {

            return;

        }

        coverHolder = article.querySelector('.' + css.cover);

        /** Compose preview */
        makePreview(coverHolder, pageId);

    }

    /**
     * Makes uploading image preview
     * @param  {Element} holder cover holder image
     * @param  {string|Number} pageId
     */
    function makePreview( holder, pageId ) {

        var input = codex.transport.input,
            files = input.files,
            reader;

        console.assert( files, 'There is no files in input');

        reader = new FileReader();
        reader.readAsDataURL(files[0]);

        holder.classList.add(css.preivew);

        reader.onload = function ( e ) {

            updateCoverImage(
                {
                    url : e.target.result,
                    target : pageId
                },
                holder,
                true // is preview mode
            );

        };

    }

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
     * Update cover after succes uploading
     * Do it after new image loading
     * @param  {Object} imageData  - new cover data
     */
    function update( imageData ) {

        var img = document.createElement('IMG');

        /** Wait for browser download and cache image */
        img.onload = function () {

            updateCoverImage(imageData);

        };

        img.src = imageData.url;

    }

    /**
     * Updates visible cover image
     * @param  {Object} imageData
     * @param  {string} imageData.url       - full cover URL
     * @param  {string} imageData.target    - page id
     * @param  {Element|null} coverHolder   - page cover holder (if known)
     * @param  {Boolean} isPreview          - pass TRUE for preview-mode
     */
    function updateCoverImage( imageData, coverHolder, isPreview ) {

        console.assert(imageData.target, 'Page id must be passed as target');

        var article = document.getElementById('js-page-' + imageData.target);

        if (!article) {

            return;

        }

        coverHolder = coverHolder || article.querySelector('.' + css.cover);

        if ( !coverHolder ) {

            codex.core.log('Nothing to update. Cover was not found', '[page.cover]', 'warn');
            return;

        }

        if (isPreview) {

            coverHolder.classList.add(css.preview);

        } else {

            coverHolder.classList.remove(css.preview);

        }

        /** Remove button svg icon */
        coverHolder.innerHTML = '';

        coverHolder.style.backgroundImage = 'url(' + imageData.url + ')';
        coverHolder.classList.remove(css.setCover, css.setCoverShowed);

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