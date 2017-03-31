/**
 * Admin module
 */

module.exports = (function () {

    var emptyBrandingClass = 'branding--empty';
    var loadingClass       = 'branding--loading';
    var preloaderClass     = 'branding__preloader';
    var preloadShown       = 'branding__preloader--shown';

    /**
     * Branding holder
     * @type {Element|null}
     */
    var wrapper = null;

    /**
     * Initialization
     * @fires preload
     */
    var init = function () {

        wrapper = document.getElementById('brandingSection');

        if ( !wrapper ) {

            return;

        }

        var url = wrapper.dataset.src;

        preload( url );

    };

    /**
     * Shows blurred preview and change it to the full-view image
     * @param  {String} fullUrl          - URL of original image
     * @return {String|null} previewUrl  - pass to renew preloader
     */
    var preload = function ( fullUrl, previewUrl ) {

        var preloader = wrapper.querySelector('.' + preloaderClass),
            img = document.createElement('IMG');

        if ( previewUrl ) {

            preloader.style.backgroundImage = "url('" + previewUrl + "')";
            preloader.classList.add(preloadShown);

        }

        img.src = fullUrl;
        img.onload = function () {

            wrapper.style.backgroundImage = "url('" + fullUrl + "')";
            preloader.classList.remove(preloadShown);

        };

    };



    /**
     * changes site branding
     * @private
     */
    var change = function () {

        codex.transport.init({

            url : '/upload/4',
            accept : 'image/*',
            beforeSend: function () {

                wrapper.classList.add(loadingClass);

            },
            success : function (result) {

                var response = JSON.parse(result),
                    url,
                    preview;

                wrapper.classList.remove(loadingClass);

                if ( response.success ) {

                    url = response.data.url;
                    preview = '/upload/branding/preload_' + response.data.name + '.jpg';

                    if ( wrapper.classList.contains(emptyBrandingClass) ) {

                        wrapper.classList.remove(emptyBrandingClass);

                    }

                    preload( url, preview );

                } else {

                    codex.alerts.show({
                        type: 'error',
                        message: 'Uploading failed'
                    });

                }

            },
            error: function () {

                wrapper.classList.remove(loadingClass);

                codex.alerts.show({
                    type: 'error',
                    message: 'Error while uploading branding image;'
                });

            }

        });

    };

    return {
        init: init,
        change : change
    };

})({});
